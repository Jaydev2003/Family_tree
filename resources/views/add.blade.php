@extends('layout.treeLayout')

@section('content')
    <link rel="stylesheet" href="{{ asset('tree/add.css') }}">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <main id="main" class="main">

        <div id="error-container" class="error-container">
            <div id="error-message" class="error-message"></div>
        </div>

        <div id="success-container" class="success-container">
            <div id="success-message" class="success-message"></div>
        </div>
        <div class="back-btn">
            <a href="list" class="btn btn-primary" style="background-color: #0f5685;"><i
                    class="fa-solid fa-arrow-left"></i></a>
        </div>

        <div class="container">
            @if ($parent)
                <div class="dropdown-wrapper">
                    <select class="form-select" id="parent-dropdown">
                        <option selected disabled value="0">Select Parent</option>
                        <option value="{{ $parent->id }}" data-address="{{ $parent->address }}">
                            {{ $parent->name }}
                        </option>
                    </select>
                    <button type="button" class="btn btn-success" id="parent-plus-btn">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            @else
                <div class="alert alert-danger mt-3" role="alert">
                    No parent data available. Please add a parent first.
                </div>
            @endif
            <div id="dynamic-container"></div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            $(document).ready(function () {
                $(document).on('submit', '.child-form', function (event) {
                    event.preventDefault();
                    const form = $(this);
                    const formData = new FormData(form[0]);
                    const submitButton = form.find('button[type="submit"]');
                    const addressField = form.find('#address');
                    const nameField = form.find('input[name="name"]');
                    const emailField = form.find('input[name="email"]');
                    const phoneField = form.find('input[name="phone"]');
                    const genderField = form.find('select[name="gender"]');
                    const statusField = form.find('select[name="status"]');
                    const relationField = form.find('select[name="relation"]');
                    const formContainer = form.closest('.child-form-container');
                    const dropdownWrapper = form.closest('.dropdown-wrapper');
                    let addressToStore = addressField.val();

                    if (addressField.prop('disabled')) {
                        addressToStore = form.find('input[name="root_parent_address"]').val();
                    }

                    formData.set('address', addressToStore);

                    $('#error-message').text('').hide();
                    $('#success-message').text('').hide();
                    submitButton.prop('disabled', true).text('Checking...');
                    $.ajax({
                        url: '{{ route('check.unique.email') }}',
                        type: 'POST',
                        data: {
                            email: emailField.val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.exists) {
                                $('#error-message').html(
                                    'Email already exists. Please use a different email.')
                                    .show().css('background-color', '#f8d7da');
                                setTimeout(function () {
                                    $('#error-message').fadeOut();
                                }, 2000);
                                submitButton.prop('disabled', false).text('Submit');
                                return;

                            }

                            $.ajax({
                                url: '{{ route('check.unique.phone') }}',
                                type: 'POST',
                                data: {
                                    phone: phoneField.val(),
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    if (response.exists) {
                                        $('#error-message').html(
                                            'Phone number already exists. Please use a different phone number.'
                                        ).show().css('background-color',
                                            '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }

                                    if (!nameField.val() && !emailField.val() && !
                                        phoneField.val() && !genderField.val() && !
                                        statusField.val() && !relationField.val()) {
                                        $('#error-message').html('Please Fill The Form')
                                            .show().css('background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    } else if (!nameField.val()) {
                                        $('#error-message').html(
                                            'Name field is required.').show().css(
                                                'background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }

                                    if (!emailField.val()) {
                                        $('#error-message').html(
                                            'Email field is required.').show().css(
                                                'background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }

                                    const emailValue = emailField.val();
                                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                                    if (!emailRegex.test(emailValue)) {
                                        $('#error-message').html(
                                            'Please enter a valid email address.')
                                            .show().css('background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }
                                    if (!genderField.val()) {
                                        $('#error-message').html(
                                            'Gender field is required.').show().css(
                                                'background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }
                                    if (!statusField.val()) {
                                        $('#error-message').html(
                                            'Status field is required.').show().css(
                                                'background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }
                                    if (!relationField.val()) {
                                        $('#error-message').html(
                                            'Relation field is required.').show()
                                            .css('background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }
                                    if (!phoneField.val()) {
                                        $('#error-message').html(
                                            'Phone Number is required.').show().css(
                                                'background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }

                                    const phoneValue = phoneField.val();
                                    const phoneRegex = /^[0-9]{10}$/;
                                    if (!phoneRegex.test(phoneValue)) {
                                        $('#error-message').html(
                                            'Phone number must be exactly 10 digits.'
                                        ).show().css('background-color',
                                            '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }

                                    if (!addressToStore) {
                                        $('#error-message').html(
                                            'Address field is required.').show()
                                            .css('background-color', '#f8d7da');
                                        setTimeout(function () {
                                            $('#error-message').fadeOut();
                                        }, 2000);
                                        submitButton.prop('disabled', false).text(
                                            'Submit');
                                        return;
                                    }

                                    $.ajax({
                                        url: '{{ route('childstore') }}',
                                        type: 'POST',
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        success: function (response) {
                                            console.log(response);

                                            if (response.success) {
                                                const dropdownWrapper = form.closest('.dropdown-wrapper');
                                                const childDropdown = dropdownWrapper.next().find('select');
                                                const parentDropdownWrapper = dropdownWrapper.closest('.dropdown-wrapper');
                                                const container = document.querySelector('#dynamic-container');

                                                if (childDropdown.length > 0) {

                                                   
                                                    const newOption = $('<option>')
                                                        .val(response.child.id)
                                                        .text(response.child.name)
                                                        .data('gender', response.child.gender)
                                                        .data('status', response.child.status)
                                                        .data('name', response.child.name);

                                                    childDropdown.append(newOption);
                                                    childDropdown.val(response.child.id);
                                                } else {
                                                    
                                                    const newDropdownWrapper = $('<div>').addClass('dropdown-wrapper mt-3');
                                                    const newChildDropdown = $('<select>').addClass('form-select');
                                                    newChildDropdown.append('<option selected disabled value="0">Select Child</option>');

                                                    const newOption = $('<option>')
                                                        .val(response.child.id)
                                                        .text(response.child.name)
                                                        .data('gender', response.child.gender)
                                                        .data('status', response.child.status)
                                                        .data('name', response.child.name);

                                                    newChildDropdown.append(newOption);

                                                    const newPlusButton = $('<button>')
                                                        .attr('type', 'button')
                                                        .addClass('btn btn-success')
                                                        .html('<i class="fas fa-plus"></i>');

                                                    newDropdownWrapper.append(newChildDropdown).append(newPlusButton);
                                                    container.appendChild(newDropdownWrapper[0]);

                                                    newChildDropdown.on('change', function () {
                                                        const selectedChildId = newChildDropdown.val();
                                                        const dropdownIndex = Array.from(container.children).indexOf(newDropdownWrapper[0]);
                                                        while (container.children.length > dropdownIndex + 1) {
                                                            container.removeChild(container.lastChild);
                                                        }
                                                        const selectedChildOption = newDropdownWrapper.find('select').find('option:selected');
                                                        const childGender = selectedChildOption.data('gender');
                                                        const childStatus = selectedChildOption.data('status');
                                                        if (childGender === 'Male' && childStatus === 'Married') {
                                                            createChildDropdown(selectedChildId, rootParentAddress, container);
                                                        }
                                                    });

                                                    newPlusButton.on('click', function () {
                                                        const newDropdownWrapper = $(this).closest('.dropdown-wrapper');
                                                        const selectedChildId = newDropdownWrapper.find('select').val();

                                                        if (!selectedChildId || selectedChildId == 0) {
                                                            $('#error-message').text('Please select a child first.').show();
                                                            setTimeout(() => $('#error-message').fadeOut(), 2000);
                                                            return;
                                                        }

                                                        const selectedChildOption = newDropdownWrapper.find('select').find('option:selected');
                                                        const childGender = selectedChildOption.data('gender');
                                                        const childStatus = selectedChildOption.data('status');
                                                        const childName = selectedChildOption.data('name');

                                                        if (childGender === 'Male' && childStatus === 'Married') {
                                                            openChildForm(newDropdownWrapper[0], selectedChildId, rootParentAddress);
                                                        } else {
                                                            $('#error-message').text(`${childName} is not Married`).show();
                                                            setTimeout(() => $('#error-message').fadeOut(), 2000);
                                                        }

                                                    });
                                                }

                                                $('#success-message').html(response.child.name + ' Data stored successfully!').show();
                                                $('#success-container').show();

                                                setTimeout(function () {
                                                    $('#success-message').fadeOut();
                                                    $('#success-container').fadeOut();
                                                }, 2000);

                                                form.remove();
                                                formContainer.remove();
                                                submitButton.prop('disabled', false).text('Submit');
                                            } else {
                                                $('#error-message')
                                                    .html('<span class="text-danger">An error occurred while submitting the form.</span>')
                                                    .show()
                                                    .css('background-color', '#f8d7da');

                                                setTimeout(function () {
                                                    $('#error-message').fadeOut();
                                                }, 2000);

                                                submitButton.prop('disabled', false).text('Submit');
                                            }
                                        },



                                        error: function (xhr, status, error) {
                                            $('#error-message')
                                                .html('<span class="text-danger">An error occurred while submitting the form.</span>')
                                                .show()
                                                .css('background-color', '#f8d7da');

                                            setTimeout(function () {
                                                $('#error-message').fadeOut();
                                            }, 2000);

                                            submitButton.prop('disabled', false).text('Submit');
                                        }
                                    });
                                },

                            });
                        },

                    });
                });
            });

            ///////////////////////////*** Create New childForm ***///////////////////////////

            let formCounter = 0;
            const parentDropdown = document.getElementById('parent-dropdown');
            const parentPlusBtn = document.getElementById('parent-plus-btn');
            const dynamicContainer = document.getElementById('dynamic-container');
            const childData = @json($child);
            let rootParentAddress = '';

            const openChildForm = (container, parentId, rootParentAddress) => {
                formCounter++;
                const formWrapper = document.createElement('div');
                formWrapper.classList.add('child-form-container', 'mb-3');
                formWrapper.setAttribute('data-parent-id', parentId);
                formWrapper.setAttribute('data-form-counter', formCounter);

                formWrapper.innerHTML = `
            <form  method="POST" class="child-form">
            @csrf
            <input type="hidden" name="parent_id" value="${parentId}">
            <input type="hidden" name="root_parent_address" value="${rootParentAddress}">
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control form-control-lg" name="name" id="name" >
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-lg" name="email" id="email" >
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-control form-control-lg" >
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control form-control-lg" >
                        <option value="" disabled selected>Select Status</option>
                        <option value="Married">Married</option>
                        <option value="Unmarried">Un-married</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="relation" class="form-label">Relation</label>
                    <select name="relation" id="relation" class="form-control form-control-lg" >
                        <option value="" disabled selected>Select Relation</option>
                        <option value="father">Father</option>
                        <option value="mother">Mother</option>
                        <option value="brother">Brother</option>
                        <option value="sister">Sister</option>
                        <option value="son">Son</option>
                        <option value="daughter">Daughter</option>
                        <option value="wife">Wife</option>
                    </select>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control form-control-lg" name="phone" id="phone" >
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-md-12">
                    <label for="address" class="form-label">Address</label>
                    <div class="input-wrapper" style="position: relative;">
                        <input type="text" class="form-control form-control-lg address" name="address" id="address" value="${rootParentAddress}" >
                        <i class="fas fa-toggle-off" id="toggle-address" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn-child btn-primary btn-lg" style="background-color:#012970;">Submit</button>
                <button type="button" class="btn-child btn-primary btn-lg" id="close-btn" style="background-color:#012970;">Close</button>
            </div>
        `;

                container.appendChild(formWrapper);

                const addressField = formWrapper.querySelector('#address');
                addressField.disabled = true;

                const toggleButton = formWrapper.querySelector('#toggle-address');
                toggleButton.addEventListener('click', () => {
                    addressField.disabled = !addressField.disabled;
                    toggleButton.classList.toggle('fa-toggle-on');
                    toggleButton.classList.toggle('fa-toggle-off');
                    if (addressField.disabled) {
                        addressField.value = rootParentAddress;
                    }
                });

                const closeButton = formWrapper.querySelector('#close-btn');
                closeButton.addEventListener('click', () => {
                    formWrapper.remove();
                });

                clearFormFields(formWrapper);
            };

            const clearFormFields = (formWrapper) => {
                formWrapper.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });
            };


            ///////////////////////////*** Create New Dropdown with Form ***///////////////////////////

            const createChildDropdown = (parentId, rootParentAddress, container) => {
                const children = childData.filter(child => child.parent_id == parentId);

                if (children.length === 0) {
                    const selectedWrapper = container.querySelector('.dropdown-wrapper:last-child') || container;
                    openChildForm(selectedWrapper, parentId, rootParentAddress);
                    return;
                }

                const dropdownWrapper = document.createElement('div');
                dropdownWrapper.classList.add('dropdown-wrapper', 'mt-3');

                const childDropdown = document.createElement('select');
                childDropdown.classList.add('form-select');
                childDropdown.innerHTML = '<option selected disabled value="0">Select Child</option>';

                children.forEach(child => {
                    const option = document.createElement('option');
                    option.value = child.id;
                    option.textContent = child.name;
                    option.dataset.gender = child.gender;
                    option.dataset.status = child.status;
                    option.dataset.name = child.name;
                    childDropdown.appendChild(option);
                });

                const plusButton = document.createElement('button');
                plusButton.type = 'button';
                plusButton.classList.add('btn', 'btn-success', 'ms-2');
                plusButton.innerHTML = '<i class="fas fa-plus"></i>';

                dropdownWrapper.appendChild(childDropdown);
                dropdownWrapper.appendChild(plusButton);
                container.appendChild(dropdownWrapper);

                childDropdown.addEventListener('change', () => {
                    const selectedChildId = childDropdown.value;
                    const selectedChildOption = childDropdown.selectedOptions[0];

                    const dropdownIndex = Array.from(container.children).indexOf(dropdownWrapper);
                    while (container.children.length > dropdownIndex + 1) {
                        container.removeChild(container.lastChild);
                    }

                    const childGender = selectedChildOption.dataset.gender;
                    const childStatus = selectedChildOption.dataset.status;

                    if (childGender === 'Male' && childStatus === 'Married') {
                        createChildDropdown(selectedChildId, rootParentAddress, container);
                    }
                });

                plusButton.addEventListener('click', () => {
                    const selectedChildId = childDropdown.value;
                    const selectedChildOption = childDropdown.selectedOptions[0];

                    if (!selectedChildId || selectedChildId == 0) {
                        $('#error-message').text('Please select a child first.').show();
                        setTimeout(() => $('#error-message').fadeOut(), 2000);
                        return;
                    }

                    const childGender = selectedChildOption.dataset.gender;
                    const childStatus = selectedChildOption.dataset.status;
                    const childname = selectedChildOption.dataset.name;

                    if (childGender === 'Male' && childStatus === 'Married') {
                        openChildForm(dropdownWrapper, selectedChildId, rootParentAddress);
                    } else {
                        $('#error-message').text(`${childname} is not Married`).show();
                        setTimeout(() => $('#error-message').fadeOut(), 2000);
                    }
                });
            };


            ///////////////////////////*** Parent Dropdown Event ***///////////////////////////

            parentDropdown.addEventListener('change', () => {
                const selectedParentId = parentDropdown.value;
                dynamicContainer.innerHTML = '';
                if (!selectedParentId || selectedParentId == 0) {
                    $('#error-message').text('Please select a valid parent.').show();
                    setTimeout(() => $('#error-message').fadeOut(), 2000);
                    return;
                }
                const selectedOption = parentDropdown.querySelector(`option[value="${selectedParentId}"]`);
                rootParentAddress = selectedOption ? selectedOption.dataset.address : '';
                createChildDropdown(selectedParentId, rootParentAddress, dynamicContainer);
            });

            ///////////////////////////*** Parent Dropdown Plus Btn ***///////////////////////////

            parentPlusBtn.addEventListener('click', () => {
                const selectedParentId = parentDropdown.value;

                if (!selectedParentId || selectedParentId == 0) {
                    $('#error-message').text('Please select a parent first.').show();
                    setTimeout(() => $('#error-message').fadeOut(), 2000);
                    return;
                }

                const existingForms = dynamicContainer.querySelectorAll('.child-form-container');
                existingForms.forEach(form => form.remove());

                const rootParentDropdownWrapper = parentPlusBtn.closest('.dropdown-wrapper');
                if (!rootParentDropdownWrapper) {
                    $('#error-message').text('Dropdown container not found.').show();
                    setTimeout(() => $('#error-message').fadeOut(), 2000);
                    return;
                }

                const selectedOption = parentDropdown.querySelector(`option[value="${selectedParentId}"]`);
                const rootParentAddress = selectedOption ? selectedOption.dataset.address : '';

                openChildForm(rootParentDropdownWrapper, selectedParentId, rootParentAddress);
            });

            const sidebar = document.querySelector('.sidebar');
            const backBtn = document.querySelector('.back-btn');
            const toggleButton = document.querySelector('.toggle-sidebar-btn');

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('hidden-sidebar');
                backBtn.classList.toggle('sidebar-visible');
                document.querySelector('.chart-container').style.marginLeft = sidebar.classList.contains('hidden-sidebar') ? '0' : '250px';
            });

        });

    </script>
@endsection