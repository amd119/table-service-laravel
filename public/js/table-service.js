/**
 * ------------------------------------
 * Global Variables
 * ------------------------------------
 */
const csrf_token = $("meta[name=csrf-token]").attr("content"),
     auth_id = $("meta[name=auth_id]").attr("content"),
     url = $("meta[name=url]").attr("content");

/**
* ------------------------------------
* Laravel Notifications
* ------------------------------------
*/
function showNotification(type, message) {
    // Create the notification HTML that mimics Laravel's flash message format
    const notificationHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    $('.notification-container').html(notificationHTML);
    setTimeout(() => $('.alert').alert('close'), 2000);
}

/**
* ------------------------------------
* Initialize Select2 Elements
* ------------------------------------ 
*/
function initializeSelect2Elements() {
    // only initialize select elements that haven't been initialized yet
    $('select[data-select2-selector="default"]').not('.select2-hidden-accessible').each(function() {
        $(this).select2({
            minimumResultsForSearch: Infinity,
            dropdownCssClass: "select2-dropdown-light",
            theme: "bootstrap-5",
            width: '100%' // make Select2 responsive
        });
    });
}

/**
* ------------------------------------
* Format Price Functions
* ------------------------------------
*/
function formatNumber(number) {

    // remove any non-numeric characters except dots
    let value = number.toString().replace(/[^0-9.]/g, '');
    // replace dots with empty string to get clean number
    value = value.replace(/\./g, '');
    // format with dots as thousand separators
    return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatPriceInput(input) {
    let value = input.val().replace(/[^0-9]/g, '');
    input.val(formatNumber(value));
}

function displayFormattedPrice(price) {
    // format database price value for display
    return formatNumber(price);
}

/**
* ------------------------------------
* Form Status Tracking
* ------------------------------------
*/
function initFormTracker(formId) {
    const form = $(formId);
    const formData = new FormData(form[0]);
    // const cancelBtn = form.find('button[data-bs-dismiss="modal"]');
    const cancelBtn = form.find('button.btn-danger');
    const closeBtn = form.find('button.btn-close');

    // store original cancel button text
    const originalBtnText = cancelBtn.text();
    
    // store original form values in form dataset
    const originalValues = {
        'cancelButtonText': originalBtnText,
    };

    // store form field values
    form.find('input:not([type="hidden"]), select').each(function() {
        const input = $(this);
        if (input.attr('name')) {
            originalValues[input.attr('name')] = input.val();
        }
    });
    
    // store the original image src
    const imagePreview = form.find('.preview-image');
    if (imagePreview.length) {
        originalValues['imageSrc'] = imagePreview.attr('src');
    }
    
    // store original values on the form element using jQuery data
    form.data('originalValues', originalValues);
    
    // check for changes in any input or select
    form.on('input change', 'input:not([type="hidden"]), select', function() {
        const hasChanges = checkFormChanges(form);
        updateCancelButton(cancelBtn, hasChanges, originalBtnText);
    });
    
    // special handling for file inputs that don't trigger input events properly
    form.find('input[type="file"]').on('change', function() {
        const hasChanges = checkFormChanges(form);
        updateCancelButton(cancelBtn, hasChanges, originalBtnText);
    });
    
    // initialize button state
    updateCancelButton(cancelBtn, false, originalBtnText);
    updateCancelButton(closeBtn, false, ''); // X button should never have text
    
    return {
        form: form,
        cancelBtn: cancelBtn,
        originalBtnText: originalBtnText
    };
}

function checkFormChanges(form) {
    const originalValues = form.data('originalValues');
    if (!originalValues) return false;
    
    let hasChanges = false;
    
    // check regular inputs and selects
    form.find('input:not([type="hidden"]):not([type="file"]), select').each(function() {
        const input = $(this);
        if (input.attr('name') && input.val() !== originalValues[input.attr('name')]) {
            hasChanges = true;
            return false; // break the loop
        }
    });
    
    // check file input
    const fileInput = form.find('input[type="file"]');
    if (fileInput.length && fileInput[0].files.length > 0) {
        hasChanges = true;
    }
    
    // check image preview source change
    const imagePreview = form.find('.preview-image');
    if (imagePreview.length && originalValues['imageSrc'] && imagePreview.attr('src') !== originalValues['imageSrc']) {
        hasChanges = true;
    }
    
    return hasChanges;
}

function updateCancelButton(button, hasChanges, originalText) {
    // check if the button is the 'X' close button or the Cancel button
    if (button.hasClass('btn-close')) {
        // the 'X' button should never have text
        button.empty().attr('data-bs-dismiss', 'modal');
        return;
    }
    
    // Handle the Cancel/Clear Form button
    if (hasChanges) {
        // if there are changes, change button to "Clear Form" and remove dismiss attribute
        button.text('Clear Form').removeAttr('data-bs-dismiss');
    } else {
        // if no changes, ensure button has correct text and dismiss attribute
        button.text(originalText).attr('data-bs-dismiss', 'modal');
    }
}

/**
* ------------------------------------
* Image Preview Functions
* ------------------------------------
*/
function setupImagePreview(container) {
    const fileUpload = container.find('.file-upload');
    const preview = container.find('.preview-image');
    const uploadButton = container.find('.upload-button');
    
    if (!fileUpload.length || !preview.length) return;
    
    // Store original image src for reset functionality
    const originalImageSrc = preview.attr('src');
    preview.data('original', originalImageSrc);
    
    // Create the remove image button if it doesn't exist
    let removeButton = container.find('.remove-image');
    if (!removeButton.length) {
        removeButton = $('<div class="position-absolute top-0 start-0 bg-danger text-white rounded-circle p-1 m-1 c-pointer remove-image"><i class="feather feather-x" aria-hidden="true"></i></div>');
        removeButton.css({
            'zIndex': '10',
            'display': 'none' // Hidden by default
        });
        container.find('.position-relative').append(removeButton);
    }
    
    // Handle file selection
    fileUpload.off('change').on('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
                removeButton.css('display', 'block'); // Show remove button
                
                // Trigger change event for form tracking
                fileUpload.trigger('change');
            };
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Handle click on upload button
    uploadButton.off('click').on('click', function() {
        fileUpload.click();
    });
    
    // Handle remove button click
    removeButton.off('click').on('click', function(e) {
        e.stopPropagation(); // Prevent triggering upload button click
        preview.attr('src', originalImageSrc);
        fileUpload.val(''); // Clear the file input
        removeButton.css('display', 'none');
        
        // Trigger change event for form tracking
        fileUpload.trigger('change');
    });
}

/**
* ------------------------------------
* Global Form Update Function
* ------------------------------------
*/
function updateFormData(e) {
    if (e) {
        e.preventDefault();
    }
    
    const form = $(this);
    const modal = form.closest('.modal');
    const submitBtn = form.find('button[type="submit"]');
    const originalBtnText = submitBtn.html();

    // Clear previous errors first
    form.find('.invalid-feedback').remove();
    form.find('.is-invalid').removeClass('is-invalid');

    // Show loading state
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Processing...');

    $.ajax({
        url: form.attr('action'),
        method: form.attr('method'),
        data: new FormData(form[0]),
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': csrf_token
        },
        success: function(response) {
            // store the success message in sessionStorage to show after reload
            sessionStorage.setItem('notification', JSON.stringify({
                type: 'success',
                message: response.message
            }));
            
            // hide modal and reload
            modal.modal('hide');
            location.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) { // validation error
                const errors = xhr.responseJSON.errors || {};
                
                // loop through each error and show it under the corresponding field
                Object.keys(errors).forEach(function(field) {
                    const input = form.find(`[name="${field}"]`);
                    if (input.length) {
                        input.addClass('is-invalid');
                        
                        // add error message after the input or its parent div
                        const parent = input.closest('.input-group') || input.parent();
                        
                        // check if feedback div already exists
                        let feedbackDiv = parent.next('.invalid-feedback');
                        if (!feedbackDiv.length) {
                            feedbackDiv = $('<div class="invalid-feedback"></div>');
                            parent.after(feedbackDiv);
                        }
                        
                        feedbackDiv.text(errors[field][0]).show();
                    }
                });
                
                // show general error message if there's one
                const errorMessage = xhr.responseJSON?.message || xhr.statusText || "Validation failed";
                showNotification('danger', errorMessage);
            } else {
                let errorMessage = xhr.responseJSON?.message || xhr.statusText || "An error occurred while updating the menu";
                showNotification('danger', errorMessage);
            }
        },
        complete: function() {
            submitBtn.prop('disabled', false).html(originalBtnText);
        }
    });
}

/**
* ------------------------------------
* Form Reset Functions
* ------------------------------------
*/
function clearForm(form) {
    if (!form) return;
    
    const originalValues = form.data('originalValues');
    if (!originalValues) return;
    
    // Reset regular inputs
    form.find('input:not([type="hidden"]), select').each(function() {
        const input = $(this);
        const name = input.attr('name');
        
        if (!name) return;
        
        if (input.attr('type') === 'file') {
            input.val('');
            const container = input.closest('.position-relative');
            if (container) {
                const preview = container.find('.preview-image');
                const removeBtn = container.find('.remove-image');
                if (preview && originalValues['imageSrc']) {
                    preview.attr('src', originalValues['imageSrc']);
                }
                if (removeBtn) {
                    removeBtn.css('display', 'none');
                }
            }
        } else if (originalValues[name] !== undefined) {
            input.val(originalValues[name]);
            
            // For select elements, force a UI update
            if (input.is('select')) {
                input.trigger('change');
            }
        }
    });
    
    // Reset Select2 elements if used
    if (window.jQuery && window.jQuery.fn.select2) {
        jQuery(form).find('select').trigger('change');
    }

    // Reset Cancel button to original state
    const cancelBtn = form.find('button.btn-danger');
    if (cancelBtn.length) {
        cancelBtn.text(originalValues['cancelButtonText'] || 'Cancel').attr('data-bs-dismiss', 'modal');
    }
    
    // Ensure X button has no text
    const closeBtn = form.find('button.btn-close');
    if (closeBtn.length) {
        closeBtn.empty().attr('data-bs-dismiss', 'modal');
    }
    
    Swal.fire({
        title: 'Form Cleared',
        text: 'All form fields have been reset to their original values.',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

/**
* ------------------------------------
* Universal Delete Function
* ------------------------------------
*/
function deleteItem(options) {
    const { 
        itemId, 
        itemType = 'item',
        deleteSelector, 
        formId, 
        hiddenInputName, 
        endpoint 
    } = options;
    
    const itemRow = $(`${deleteSelector}[data-id="${itemId}"]`).closest('tr.single-item');

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            // Set the id in hidden input
            $(`#${hiddenInputName}`).val(itemId);

            // Get form data for ajax submission
            const form = $(`#${formId}`);
            const formData = new FormData(form[0]);
            
            $.ajax({
                method: form.attr('method'),
                url: endpoint,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Remove the row from the table without page refresh
                        itemRow.fadeOut(400, function() {
                            $(this).remove();
                            
                            // Re-number the rows if needed
                            $('#leadList tbody tr').each(function(index) {
                                $(this).find('td:first').text(index + 1);
                            });
                        
                            showNotification('success', response.message);
                        });
                    } else {
                        showNotification('danger', response.message || `Failed to delete ${itemType}.`);
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = "An error occurred during deletion.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showNotification('danger', errorMessage);
                }
            });
        }
    });
}

function formatResult(state) {
    if (!state.id) return state.text; // handle empty states
    const bgClass = state.element.dataset.bg || ''; // get data-bg attribute
    return $('<span class="hstack gap-2">' +
               '<span class="wd-7 ht-7 rounded-circle ' + bgClass + '"></span>' + 
               state.text +
           '</span>')[0]; // return the DOM element for select2
}

function formatSelection(state) {
    if (!state.id) return state.text;
    const bgClass = state.element.dataset.bg || '';
    return $('<span class="hstack gap-2">' +
               '<span class="wd-7 ht-7 rounded-circle ' + bgClass + '"></span>' +
               state.text +
           '</span>')[0];
}

/**
 * ------------------------------------
 * Order Form Management
 * ------------------------------------
 */
function initOrderForm() {
    let menuItemIndex = 0;
    const selectedMenus = new Set();
    
    // Track initial menu selection
    $('.menu-select').each(function() {
        const value = $(this).val();
        if (value) {
            selectedMenus.add(value);
            $(this).data('previous-value', value);
        }
    });

    // addMenuItem function
    $('#addMenuItem').on('click', function() {
        menuItemIndex++;
        
        // Get available menus (those not already selected)
        const menuOptions = [];
        $('.menu-data').each(function() {
            const menuId = $(this).data('id');
            const menuName = $(this).data('name');
            const menuPrice = $(this).data('price');
            
            if (!selectedMenus.has(menuId.toString())) {
                menuOptions.push({
                    id: menuId,
                    text: `${menuName} - Rp ${formatNumber(menuPrice)}`,
                    price: menuPrice
                });
            }
        });
    
        if (menuOptions.length === 0) {
            showNotification('warning', "All menu items have been selected.");
            return;
        }
    
        const newRow = `
            <div class="menu-item-row row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Menu <span class="text-danger">*</span></label>
                    <select class="form-select menu-select" name="menu_items[${menuItemIndex}][idmenu]" data-select2-selector="default" required>
                        <option value="">Select Menu</option>
                        ${menuOptions.map(option => `<option value="${option.id}" data-price="${option.price}">${option.text}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="text" class="form-control quantity-input" name="menu_items[${menuItemIndex}][jumlah]" value="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger remove-menu-item">
                        <i class="feather-trash-2"></i> Remove
                    </button>
                </div>
            </div>
        `;
        
        // Append the new row to the container
        $('#menuItemsContainer').append(newRow);
    
        // Directly apply Select2 to the new select element
        const newSelect = $('#menuItemsContainer .menu-item-row:last-child .menu-select');
        if (!newSelect.hasClass("select2-hidden-accessible")) {
            newSelect.select2({
                dropdownCssClass: "select2-dropdown-light",
                theme: "bootstrap-5",  // Add this line to match the theme
                width: '100%' // Make new row Menu Select2 responsive
            });
        }
    
        // Set up change event for the new select
        newSelect.on('change', function() {
            handleMenuSelectChange($(this));
        });
    
        // Show all remove buttons when we have more than one row
        if ($('.menu-item-row').length > 1) {
            $('.remove-menu-item').show();
        }   
    });

    // Remove menu item
    $(document).on('click', '.remove-menu-item', function() {
        // Get the menu ID that's about to be removed
        const menuId = $(this).closest('.menu-item-row').find('.menu-select').val();
        if (menuId) {
            selectedMenus.delete(menuId);
        }
        
        $(this).closest('.menu-item-row').remove();
        
        // Hide remove buttons if only one row remains
        if ($('.menu-item-row').length === 1) {
            $('.remove-menu-item').hide();
        }
        
        // Update available options in remaining selects
        updateMenuSelects();
    });

    // Set up change event for existing menu selects
    $('.menu-select').on('change', function() {
        handleMenuSelectChange($(this));
    });

    // Function to handle menu select change events
    function handleMenuSelectChange(selectElement) {
        const previousValue = selectElement.data('previous-value');
        const currentValue = selectElement.val();
        
        // Remove the previous value from the set of selected menus
        if (previousValue) {
            selectedMenus.delete(previousValue);
        }
        
        // Add the new value to the set of selected menus
        if (currentValue) {
            selectedMenus.add(currentValue);
        }
        
        // Store the current value as the previous value for next change
        selectElement.data('previous-value', currentValue);
        
        // Update all dropdown options in other selects
        updateMenuSelects();
    }

    // Function to update all menu selects with currently available options
    function updateMenuSelects() {
        $('.menu-select').each(function() {
            const currentSelect = $(this);
            const currentValue = currentSelect.val();
            
            // Keep track of all menus that are selected in other dropdowns
            const otherSelectedMenus = new Set();
            $('.menu-select').not(this).each(function() {
                const value = $(this).val();
                if (value) {
                    otherSelectedMenus.add(value);
                }
            });
            
            // Update each option's disabled state
            currentSelect.find('option').each(function() {
                const optionValue = $(this).val();
                if (optionValue && optionValue !== currentValue) {
                    $(this).prop('disabled', otherSelectedMenus.has(optionValue));
                }
            });
        });
    }

    // Initialize form validation
    $('#createOrderForm').on('submit', function(e) {
        let valid = true;
        
        // Check if at least one menu item is selected
        if ($('.menu-select').length === 0) {
            showNotification('danger', 'Please add at least one menu item.');
            valid = false;
        }
        
        // Check if all menu items have valid selections
        $('.menu-select').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                valid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!valid) {
            e.preventDefault();
        }
    });
    
    // Initialize the menu selects on page load
    updateMenuSelects();
}

/**
 * ------------------------------------
 * Order page initialization
 * ------------------------------------
 */
function initOrderPage() {
    // Check if we're on the order create page
    if ($('#createOrderForm').length) {
        initOrderForm();
    }
}

/**
 * ------------------------------------
 * On Dom Load
 * ------------------------------------
*/
$(document).ready(function() {

    // check for stored notifications from previous page actions
    const storedNotification = sessionStorage.getItem('notification');
    if (storedNotification) {
        try {
            const notification = JSON.parse(storedNotification);
            showNotification(notification.type, notification.message);
            sessionStorage.removeItem('notification'); // clear after showing
        } catch(e) {
            console.error('Error parsing stored notification:', e);
        }
    }

    // automatically close alert after 2 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 2000);

    // only allow numbers in numbers inputs
    $(document).on('input', '.quantity-input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value === '') {
            this.value = '1';
        }
    });

    // Initialize all Select2 elements
    // initializeSelect2Elements();

    // disable select status search input
    $('select[name="status"], select[name="role"], select[name="gender"], select[name="jenis_kelamin"], select[name="metode_pembayaran]').select2({
        minimumResultsForSearch: Infinity, // disable search
        theme: "bootstrap-5",               // apply Bootstrap-5 theme
        templateResult: formatResult, // customize result rendering
        templateSelection: formatSelection // customize selection rendering
    });
    
    // format price fields on page load
    $('[name="harga"]').each(function() {
        const input = $(this);
        const originalValue = input.val();
        input.val(displayFormattedPrice(originalValue));
    });
    
    // format input harga on input event
    $("body").on('input', '[name="harga"]', function() {
        formatPriceInput($(this));
    });
    
    // setup image previews for all containers on page load
    $('body').find('.position-relative').each(function() {
        setupImagePreview($(this));
    });

    // Store original values when modal opens
    $('.edit-order').on('click', function() {
        const idpelanggan = $(this).data('id');
        const modalId = `#editordermodal-${idpelanggan}`;
        const form = $(`#editForm-${idpelanggan}`);
        
        // Store original values when modal opens
        form.find('input, select').each(function() {
            $(this).data('original-value', $(this).val());
        });
        
        $(modalId).modal('show');
    });
    
    // Form submission with change detection
    $('form[id^="editForm-"]').on('submit', function(e) {
        const form = $(this);
        let hasChanges = false;
        
        // Check if any field has changed
        form.find('input, select').each(function() {
            const originalValue = $(this).data('original-value');
            const currentValue = $(this).val();
            
            if (originalValue !== currentValue) {
                hasChanges = true;
                $(this).addClass('field-changed');
            }
        });
        
        if (!hasChanges) {
            e.preventDefault();
            alert('No changes detected. Please modify at least one field to update.');
        }
    });

    // show edit order modal
    $("body").on('click', '.edit-order', function(event) {
        const orderId = $(this).data('id');
        const modal = $(`#editordermodal-${orderId}`);
        const form = $(`#editForm-${orderId}`);
        
        // initialize the form tracker
        initFormTracker(`#editForm-${orderId}`);

        modal.modal('show');
    });

    // show edit user modal
    $("body").on('click', '.edit-user', function(event) {
        const userId = $(this).data('id');
        const modal = $(`#editusermodal-${userId}`);
        const form = $(`#editForm-${userId}`);
        
        // initialize the form tracker
        initFormTracker(`#editForm-${userId}`);
    
        modal.modal('show');
    });
    
    // show edit table modal
    $("body").on('click', '.edit-table', function(event) {
        const tableId = $(this).data('id');
        const modal = $(`#edittablemodal-${tableId}`);
        const form = $(`#editForm-${tableId}`);
        
        // initialize the form tracker
        initFormTracker(`#editForm-${tableId}`);
    
        modal.modal('show');
    });

    // show edit menu modal
    $("body").on('click', '.edit-menu', function(event) {
        const menuId = $(this).data('id');
        const modal = $(`#editmenumodal-${menuId}`);
        const form = $(`#editForm-${menuId}`);
        
        // initialize the form tracker
        initFormTracker(`#editForm-${menuId}`);
        
        // setup image preview for this modal
        setupImagePreview(modal);
        
        // setup price formatting for this modal
        modal.find('[name="harga"]').each(function() {
            const input = $(this);
            const originalValue = input.val();
            input.val(displayFormattedPrice(originalValue));
        });
    
        modal.modal('show');
    });
    
    // update form submission binding to use the new universal/global function
    $("body").on('submit', '[id^="editForm-"]', function(e) {
        updateFormData.call(this, e);
    });
    
    // handle Clear Form button click
    $("body").on('click', '.btn-danger:not([data-bs-dismiss="modal"])', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        if (form.length) {
            clearForm(form);
            // Reset button to Cancel after clearing
            // $(this).text('Cancel').attr('data-bs-dismiss', 'modal');
            // The button state will be updated by clearForm function
        }
    });

    // Initialize the order page
    initOrderPage();

    // delete user
    $("body").on('click', '.delete-user', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        deleteItem({
            itemId: id,
            itemType: 'user',
            deleteSelector: '.delete-user',
            formId: 'deleteUserForm',
            hiddenInputName: 'delete_iduser',
            endpoint: '/user/delete'
        });
    });

    // delete table
    $("body").on('click', '.delete-table', function(e) {
        e.preventDefault();
        let idmeja = $(this).data('id');
        deleteItem({
            itemId: idmeja,
            itemType: 'table',
            deleteSelector: '.delete-table',
            formId: 'deleteTableForm',
            hiddenInputName: 'delete_idmeja',
            endpoint: '/table/delete'
        });
    });

    // delete menu
    $("body").on('click', '.delete-menu', function(e) {
        e.preventDefault(); // Block default GET action
        let id = $(this).data('id');
        deleteItem({
            itemId: id,
            itemType: 'menu',
            deleteSelector: '.delete-menu',
            formId: 'deleteMenuForm',
            hiddenInputName: 'delete_menu_id',
            endpoint: '/menu/delete'
        });
    });
});