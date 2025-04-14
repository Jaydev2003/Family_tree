<?php

namespace App\Http\Controllers;

use App\Models\data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{


    public function dataform()
    {
        return view("form");
    }

    public function treeLayout()
    {
        $user = Auth::user();
        return view('layout.treeLayout', compact('user'));
    }

    public function main()
    {
        $user = Auth::check() ? Auth::user()->name : 'Guest';
        return view('main', compact('user'));
    }

    public function allmemberTree()
    {
        $data = Data::
            with([
                'children' => function ($query) {
                    $query->with('children');
                }
            ])
            ->get();



        $daughters = Data::whereNotNull('old_parent_id')->get()->groupBy('old_parent_id');

        $formattedData = $this->formatDataRecursive($data, $daughters);


        return view('allmemberTree', compact('formattedData'));
    }

    public function view(Request $request, $id)
    {
        $data = Data::where('id', $id)
            ->with([
                'children' => function ($query) {
                    $query->with('children');
                }
            ])
            ->get();

        $daughters = Data::whereNotNull('old_parent_id')->get()->groupBy('old_parent_id');

        $formattedData = $this->formatDataRecursive($data, $daughters);

        return view('viewtree', compact('formattedData'));
    }

    private function formatDataRecursive($data, $daughters)
    {
        $formatted = [];

        foreach ($data as $item) {
            $wife = $item->children->where('relation', 'wife')->first();
            $wifeName = $wife ? $wife->name : null;

            $children = $item->children->where('relation', '!=', 'wife')->values();

            if ($daughters->has($item->id)) {
                $children = $children->merge($daughters[$item->id]);
            }

            $formattedItem = [
                'name' => $item->name,
                'id' => (string) $item->id,
                'parent' => $item->parent_id ? (string) $item->parent_id : null,
                'email' => $item->email,
                'address' => $item->address,
                'phone' => $item->phone,
                'gender' => $item->gender,
                'wife' => $wifeName,
                'children' => $this->formatDataRecursive($children, $daughters)
            ];

            $formatted[] = $formattedItem;
        }

        return $formatted;
    }





    public function childform()
    {
        $parent = Data::whereNull('parent_id')->get();
        $child = Data::whereNotNull('parent_id')->get();

        return view('childform', compact('child', 'parent'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required',
            'gender' => 'required',
            // 'relation' => 'required',
            'email' => 'required|email|unique:data,email',
            'phone' => 'required|regex:/^[0-9]{10}$/|unique:data,phone',
            'address' => 'required|string|max:500',
        ]);

        $kutumbNo = substr(Str::uuid()->toString(), 0, 4);
        $name = $request->name;
        $parent = Data::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'status' => $request->status,

            'phone' => $request->phone,
            'address' => $request->address,
            'parent_id' => $request->parent_id,
            'kutumb_no' => $kutumbNo,
        ]);
        $parent->save();
        $parents = Data::all();

        return redirect()->route('list')
            ->with('success', $name . ' data added successfully!')
            ->with('parents', $parents);
    }


    public function getChildrenByParent(Request $request)
    {
        $parentId = $request->input('parent_id');

        if (!$parentId) {
            return response()->json(['error' => 'Parent ID is required'], 400);
        }
        $parent = data::find($parentId);

        if (!$parent) {
            return response()->json(['error' => 'Parent not found'], 404);
        }

        $children = $parent->children;

        return response()->json([
            'children' => $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                ];
            }),
        ]);
    }



    public function childstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'parent_id' => 'required|exists:data,id',
            'status' => 'required',
            'gender' => 'required',
            'relation' => 'required',
            'email' => 'required|email|unique:data,email',
            'phone' => 'required|regex:/^[0-9]{10}$/|unique:data,phone',
            'root_parent_address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $parent = Data::find($request->parent_id);
            $address = $request->root_parent_address;

            if ($request->address && $request->address !== $address) {
                $address = $request->address;
            }

            $kutumbNo = null;

            if ($request->relation === 'wife' && $request->status == 'Married' && $parent) {
                $kutumbNo = $parent->kutumb_no;
            } elseif ($request->status == 'Married') {
                $uuidInt = substr(Str::uuid()->toString(), 0, 4);
                $kutumbNo = $parent->kutumb_no . '-' . $uuidInt;
            }

            $child = Data::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'status' => $request->status,
                'relation' => $request->relation,
                'address' => $address,
                'parent_id' => $request->parent_id,
                'kutumb_no' => $kutumbNo,
            ]);

            return response()->json([
                'success' => true,
                'child' => $child,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function dropdownrecord(Request $request, $id)
    {
        $parent = data::find($id);

        if (!$parent) {
            return redirect()->route('list')->with('error', 'Parent not found');
        }

        $child = data::whereNotNull('parent_id')
            ->where('relation', '!=', 'wife')
            ->get();

        return view('add', compact('parent', 'child'));
    }



    public function listofdata(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', null);

        $parentsQuery = Data::whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->with('children');
                }
            ])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $parentsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $parents = $parentsQuery->paginate($perPage);


        $data = Data::orderBy('created_at', 'desc')->get();

        return view('list', compact('parents', 'data'));
    }



    public function edit($id)
    {
        $data = data::find($id);

        $parent = data::whereNull('parent_id')->get();
        $child = data::whereNotNull('parent_id')->get();
        if (!$data) {
            return redirect()->route('list')->with('error', 'Data not found.');
        }


        return view('edit', compact('data', 'parent'));
    }


    public function update(Request $request, $id)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'status' => 'required',
            'gender' => 'required',
            'relation' => 'required',
            'phone' => 'required|numeric|digits:10',
            'address' => 'required|string|max:500',
        ]);

        $data = data::find($id);

        if (!$data) {
            return redirect()->route('list')->with('error', 'Data not found.');
        }

        $data->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
            'gender' => $request->input('gender'),
            'relation' => $request->input('relation'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),

        ]);


        return redirect()->route('list')->with('success', 'Data updated successfully.');
    }

    public function getChildren($id)
    {
        $husband = Data::with('parent')->find($id);
        $getchild = Data::with('children')->find($id);

        $wife = Data::where('kutumb_no', $husband->kutumb_no)
            ->where('relation', 'wife')
            ->first();

        if ($getchild) {
            $data = $getchild->children;

            $oldFamilyFemales = Data::where('old_parent_id', $id)->get();

            $allChildren = $data->merge($oldFamilyFemales);

            return response()->json([
                'children' => $allChildren->unique('id'),
                'wife' => $wife,
                'husband' => $husband,
            ]);
        } else {
            return response()->json(['children' => []]);
        }
    }


    public function getnewfamilyChildren($id)
    {
        $data = Data::find($id);

        if (!$data) {
            return response()->json([]);
        }

        $parentId = $data->parent_id;
        $filteredDescendants = [];

        $getDescendants = function ($data) use (&$filteredDescendants, &$getDescendants, $parentId) {

            if ($data->id !== $parentId && $data->status === 'Unmarried' && strtolower($data->gender) === 'male') {
                $parentName = $data->parent ? $data->parent->name : 'Unknown';
                $filteredDescendants[] = [
                    'id' => $data->id,
                    'name' => "{$data->name} {{$parentName}}"
                ];
            }

            foreach ($data->children as $child) {
                $getDescendants($child);
            }
        };

        $getDescendants($data);

        return response()->json($filteredDescendants);
    }




    public function childedit($id)
    {
        $data = data::find($id);
        $name = $data->name;

        $rootParentId = $this->getRootParentId($data);

        $rootParent = data::find($rootParentId);
        $rootParentName = $rootParent ? $rootParent->name : 'No root parent found';

        $allfamily = data::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->where('id', '!=', $rootParentId)
            ->get();
        if (!$data) {
            return redirect()->route('list')->with('error', 'Data not found.');
        }

        $parent = data::whereNull('parent_id')->get();
        $child = data::whereNotNull('parent_id')->get();


        // $isParent = $data->parent_id === null;

        // $parentData = data::whereNull('parent_id')->get();

        // $hierarchicalParents = $this->getParentsSiblings($data);

        // $parentOptions = $hierarchicalParents->merge($parentData)->unique('id');
        $parentOptions = $this->getFamilyTree($rootParentId);

        // echo"</pre>".print_r($parentOptions);die();

        return view('childedit', compact('data', 'parentOptions', 'allfamily'));
    }

    private function getRootParentId($data)
    {
        if (!$data) {
            return null;
        }

        while ($data->parent_id !== null) {
            $data = Data::find($data->parent_id);
            if (!$data) {
                return null;
            }
        }

        return $data->id;
    }



    private function getFamilyTree($rootParentId)
    {
        $familyMembers = collect();
        $getDescendants = function ($parentId) use (&$getDescendants, &$familyMembers) {
            $parent = data::find($parentId);

            if ($parent) {
                $familyMembers->push($parent);
                $children = data::where('parent_id', $parentId)->where('gender', 'Male')->get();
                foreach ($children as $child) {
                    $getDescendants($child->id);
                }
            }
        };
        $getDescendants($rootParentId);

        return $familyMembers;
    }

    // private function getParentsSiblings($node)
    // {
    //     $hierarchicalParents = collect();

    //     while ($node && $node->parent) {
    //         $parent = $node->parent;
    //         $siblingsAndParent = data::where('parent_id', $parent->parent_id)->get();
    //         $hierarchicalParents = $hierarchicalParents->merge($siblingsAndParent);
    //         $node = $parent;
    //     }

    //     return $hierarchicalParents->unique('id');
    // }



    public function childupdate(Request $request, $id)
    {
        $newChildId = $request->input('new_child_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'status' => 'required',
            'gender' => 'required',
            'relation' => 'required',
            'phone' => 'required|numeric|digits:10',
            'address' => 'required|string|max:500',
        ]);

        $data = Data::find($id);

        if (!$data) {
            return redirect()->route('list')->with('error', 'Data not found.');
        }

        if ($newChildId) {
            $data->old_parent_id = $data->parent_id;
        }

        $data->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'gender' => $request->input('gender'),
            'relation' => $request->input('relation'),
            'status' => $request->input('status'),
            'address' => $request->input('address'),
            'parent_id' => $newChildId ?: $request->input('parent_id'),
            'old_parent_id' => $data->old_parent_id,
        ]);

        if ($newChildId && $request->input('status') === 'Married') {
            $newChild = Data::find($newChildId);

            if ($newChild) {

                if ($newChild->parent_id === null) {
                    $kutumbNo = $newChild->kutumb_no;

                    if ($newChild->gender === 'Male' && trim($newChild->status) === 'Unmarried') {
                        $newChild->update([
                            'status' => 'Married',
                        ]);
                    }

                    $data->update([
                        'kutumb_no' => $kutumbNo,
                        'relation' => 'wife',
                    ]);
                } else {

                    $parentKutumbNo = $newChild->parent ? $newChild->parent->kutumb_no : null;

                    if ($parentKutumbNo) {

                        $randomUUID = Str::uuid();
                        $randomAlpha = Str::random(4);
                        $kutumbNo = $parentKutumbNo . '-' . substr($randomUUID->toString(), 0, 4) . '-' . $randomAlpha;




                        if ($newChild->gender === 'Male' && $newChild->status === 'Unmarried') {

                            $newChild->update([
                                'status' => 'Married',
                                'kutumb_no' => $kutumbNo,
                            ]);
                        }

                        $data->update([
                            'kutumb_no' => $kutumbNo,
                            'relation' => 'wife',
                        ]);
                    }
                }
            }
        }

        return redirect()->route('list')->with('success', 'Data updated successfully.');
    }




    // 'kutumb_no' => $kutumbNo, 
// $kutumbNo = random_int(10000, 99999);

    public function childdelete($id)
    {
        $data = Data::find($id);
        if ($data) {
            $name = $data->name;
            $hasChild = Data::where('parent_id', $data->id)->exists();

            if ($hasChild) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Cannot delete {$name} as it has associated child records.",
                    'has_children' => true
                ]);
            }

            $data->delete();
            return response()->json([
                'status' => 'success',
                'message' => "{$name} deleted successfully.",
                'has_children' => false
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Record not found.',
            'has_children' => false
        ]);
    }

    public function checkChild($id)
    {
        $data = Data::find($id);

        if ($data) {
            $name = $data->name;
            $hasChild = Data::where('parent_id', $data->id)->exists();

            if ($hasChild) {
                return response()->json([
                    'status' => 'error',
                    'message' => "{$name} cannot be deleted as it has associated child records.",
                    'has_children' => true,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => "{$name} can be deleted.",
                'has_children' => false,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Record not found.',
            'has_children' => false,
        ]);
    }



    public function deleteParent(Request $request)
    {
        $parentId = $request->id;
        $parent = data::find($parentId);

        $name = $parent->name;

        if (!$parent) {
            return response()->json(['success' => false, 'message' => 'Parent not found.']);
        }


        if ($parent->children->isNotEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete parent with child records.']);
        }

        $parent->delete();

        return response()->json(['success' => true, 'message' => $name . ' record deleted successfully.']);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = data::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkPhone(Request $request)
    {
        $phone = $request->input('phone');
        $exists = data::where('phone', $phone)->exists();

        return response()->json(['exists' => $exists]);
    }


}

