@props(['parent'])

@if ($parent->children->isNotEmpty())
    <table class="table table-bordered mb-4">
        <thead>
            <th style="background-color: #0f5685; color:white; font-weight:bold;">Child Name</th>
            <th style="background-color: #0f5685; color:white;" colspan="2">Action</th>
        </thead>
        <tbody>
            @foreach ($parent->children as $child)
                <tr class="view" data-id="{{ $child->id }}">
                    <td class="{{ $child->children->isNotEmpty() ? 'toggle-name' : '' }}">
                        {{ $child->name }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('childedit', ['id' => $child->id]) }}" class="edit-record">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('delete', ['id' => $child->id]) }}" class="delete-record btn-sm"
                           data-name="{{ $child->name }}"
                           data-has-children="{{ $child->children->isNotEmpty() }}"
                           data-id="{{ $child->id }}">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                @if ($child->children->isNotEmpty())
                    <tr class="fold">
                        <td colspan="3">
                            <div class="fold-content">
                                <x-family-tree :parent="$child" />
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif
