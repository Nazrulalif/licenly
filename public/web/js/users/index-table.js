"use strict";

// Class definition
var DataTableSideServer = (function () {
    // Shared variables
    var table;
    var datatable;
    var filterOptions;

    var initDatatable = function () {
        datatable = $("#table-1").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            orderable: true,
            searchable: true,
            order: [[2, "desc"]],
            select: {
                style: "multi",
                selector: 'td:first-child input[type="checkbox"]',
                className: "row-selected",
            },
            ajax: {
                url: window.location.origin + window.location.pathname,
                type: "GET",
                data: function (d) {
                    // Log parameter permintaan
                    // console.log("DataTables Request Parameters:", d);
                    return d;
                },
                dataSrc: function (response) {
                    console.log("DataTables Response:", response);
                    return response.data;
                },
            },
            columns: [
                { data: "id", name: "id", orderable: false, searchable: false },
                { data: "user", name: "name", searchable: true },
                { data: "role", name: "role", searchable: false, orderable: false },
                { data: "status", name: "status", searchable: false, orderable: false },
                {
                    data: "action",
                    name: "actions",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            columnDefs: [
                {
                    target: 0,
                    orderable: false,
                    render: function (data, type, row) {
                        if (row.myself) {
                            return ``;
                        } else {
                            return `
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="${data}" data-id="${row}"/>
                            </div>
                        `;
                        }
                    },
                },
            ],
        });

        table = datatable.$;

        datatable.on("draw", function () {
            rows();
            checkboxToolbar();
            toolbars();
            KTMenu.createInstances();
            // Inisialisasi tooltip menggunakan sintaks Bootstrap standar
            var tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    };

    //Search
    var search = function () {
        const filterSearch = document.querySelector(
            '[filter-table-1="search"]'
        );
        filterSearch.addEventListener("keyup", function (e) {
            datatable.search(e.target.value).draw();
        });
    };

    //Filter
     var filter = function () {
        const button = document.querySelector('[filter-table-1="filter"]');
        filterOptions = document.querySelectorAll("[filter-index-table-1]");

        button.addEventListener("click", function () {
            // reset carian semua kolom
            datatable.columns().search("").draw(false);

            filterOptions.forEach((select) => {
                const column = select.getAttribute("filter-index-table-1");
                const value = select.value;
                if (value) {
                    // guna column carian API
                    datatable.column(column).search(value);
                }
            });
            // gambar ulang jadual
            datatable.draw();
        });
    };

    // reset filter
    var reset = function () {
        const button = document.querySelector('[filter-table-1="reset"]');

        button.addEventListener("click", function () {
            filterOptions.forEach((select) => {
                select.value = "";

                if ($(select).data("kt-select2")) {
                    $(select).val("").trigger("change");
                }
            });
            datatable.columns().search("").draw();
            datatable.search("").draw();
        });
    };

    // row function
    var rows = function () {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll(
            '[action-row-table-1="delete"]'
        );

        // Select all deactivate buttons
        const deactivateButtons = document.querySelectorAll(
            '[action-row-table-1="deactivate"]'
        );

        // Select all reactivate buttons
        const reactivateButtons = document.querySelectorAll(
            '[action-row-table-1="reactivate"]'
        );

        // Add click event listener to each delete button
        deleteButtons.forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                // Get the actual button element - handle cases where we might have clicked a child element
                let targetElement = event.target;
                while (
                    targetElement &&
                    !targetElement.hasAttribute("action-row-table-1")
                ) {
                    targetElement = targetElement.parentElement;
                }

                // If we couldn't find the button, log an error and return
                if (!targetElement) {
                    // console.error(
                    //     "Could not find target button element",
                    //     event.target
                    // );
                    return;
                }

                // Log the button for debugging
                // console.log("Delete button clicked:", targetElement);

                const tableRow = targetElement.closest("tr");
                // Get the table row and item details
                const itemName = tableRow
                    .querySelector("td:nth-child(2)")
                    .innerText.trim();

                // Get the URL directly from the button's data attribute - try multiple methods
                let deleteUrl = targetElement.getAttribute("data-id");

                // If data-id attribute doesn't work, try dataset
                if (
                    !deleteUrl &&
                    targetElement.dataset &&
                    targetElement.dataset.id
                ) {
                    deleteUrl = targetElement.dataset.id;
                }

                // Log for debugging
                // console.log("Delete URL from attribute:", deleteUrl);
                // console.log("Button attributes:", targetElement.attributes);
                console.log("Button dataset:", targetElement.dataset);

                // Make sure we have a URL before proceeding
                if (!deleteUrl) {
                    Swal.fire({
                        title: "<strong class='text-danger'>Error</strong>",
                        text: "Could not identify the delete URL. Please try again or contact support.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                        },
                    });
                    return;
                }

                // Confirmation dialog with improved text
                Swal.fire({
                    title: "<strong class='text-warning'>Delete Confirmation</strong>",
                    text: `Are you sure you want to delete "${itemName}"?`,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete it",
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Processing notification
                        Swal.fire({
                            title: "<strong class='text-info'>Deleting...</strong>",
                            text: `Deleting "${itemName}" in progress`,
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(function () {
                            // AJAX call to delete the item
                            $.ajax({
                                url: deleteUrl,
                                type: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": csrf,
                                },
                                success: function (data) {
                                    if (data.success) {
                                        Swal.fire({
                                            title: "<strong class='text-success'>Deleted Successfully</strong>",
                                            text: `"${itemName}" has been deleted successfully.`,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton:
                                                    "btn fw-bold btn-primary",
                                            },
                                        }).then(() => datatable.draw());
                                    } else {
                                        // Error notification for server-side failures
                                        Swal.fire({
                                            title: "<strong class='text-danger'>Delete Failed</strong>",
                                            text:
                                                data.message ||
                                                `Failed to delete "${itemName}".`,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton:
                                                    "btn fw-bold btn-danger",
                                            },
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    // Error notification for AJAX failures
                                    Swal.fire({
                                        title: "<strong class='text-danger'>Error Occurred</strong>",
                                        text: `Something went wrong while deleting "${itemName}".`,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "OK",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-danger",
                                        },
                                    });
                                },
                            });
                        });
                    } else if (result.dismiss === "cancel") {
                        // Cancelled notification
                        Swal.fire({
                            title: "<strong class='text-info'>Delete Cancelled</strong>",
                            text: `"${itemName}" delete operation was cancelled.`,
                            icon: "info",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                });
            });
        });

        // Add click event listener to each deactivate button
        deactivateButtons.forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                let targetElement = event.target;
                while (
                    targetElement &&
                    !targetElement.hasAttribute("action-row-table-1")
                ) {
                    targetElement = targetElement.parentElement;
                }

                if (!targetElement) {
                    return;
                }

                const tableRow = targetElement.closest("tr");
                const itemName = tableRow
                    .querySelector("td:nth-child(2)")
                    .innerText.trim();

                let deactivateUrl = targetElement.getAttribute("data-id");

                if (!deactivateUrl && targetElement.dataset && targetElement.dataset.id) {
                    deactivateUrl = targetElement.dataset.id;
                }

                if (!deactivateUrl) {
                    Swal.fire({
                        title: "<strong class='text-danger'>Error</strong>",
                        text: "Could not identify the deactivate URL. Please try again or contact support.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                        },
                    });
                    return;
                }

                // Confirmation dialog
                Swal.fire({
                    title: "<strong class='text-warning'>Deactivate Confirmation</strong>",
                    text: `Are you sure you want to deactivate "${itemName}"?`,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, deactivate it",
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-warning",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Processing notification
                        Swal.fire({
                            title: "<strong class='text-info'>Processing...</strong>",
                            text: `Deactivating "${itemName}" in progress`,
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(function () {
                            // AJAX call to deactivate the user
                            $.ajax({
                                url: deactivateUrl,
                                type: "PUT",
                                headers: {
                                    "X-CSRF-TOKEN": csrf,
                                },
                                success: function (data) {
                                    if (data.success) {
                                        Swal.fire({
                                            title: "<strong class='text-success'>Deactivated Successfully</strong>",
                                            text: `"${itemName}" has been deactivated successfully.`,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        }).then(() => datatable.draw());
                                    } else {
                                        Swal.fire({
                                            title: "<strong class='text-danger'>Deactivate Failed</strong>",
                                            text: data.message || `Failed to deactivate "${itemName}".`,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-danger",
                                            },
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire({
                                        title: "<strong class='text-danger'>Error Occurred</strong>",
                                        text: `Something went wrong while deactivating "${itemName}".`,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "OK",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-danger",
                                        },
                                    });
                                },
                            });
                        });
                    }
                });
            });
        });

        // Add click event listener to each reactivate button
        reactivateButtons.forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                let targetElement = event.target;
                while (
                    targetElement &&
                    !targetElement.hasAttribute("action-row-table-1")
                ) {
                    targetElement = targetElement.parentElement;
                }

                if (!targetElement) {
                    return;
                }

                const tableRow = targetElement.closest("tr");
                const itemName = tableRow
                    .querySelector("td:nth-child(2)")
                    .innerText.trim();

                let reactivateUrl = targetElement.getAttribute("data-id");

                if (!reactivateUrl && targetElement.dataset && targetElement.dataset.id) {
                    reactivateUrl = targetElement.dataset.id;
                }

                if (!reactivateUrl) {
                    Swal.fire({
                        title: "<strong class='text-danger'>Error</strong>",
                        text: "Could not identify the reactivate URL. Please try again or contact support.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                        },
                    });
                    return;
                }

                // Confirmation dialog
                Swal.fire({
                    title: "<strong class='text-success'>Reactivate Confirmation</strong>",
                    text: `Are you sure you want to reactivate "${itemName}"?`,
                    icon: "question",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, reactivate it",
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-success",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Processing notification
                        Swal.fire({
                            title: "<strong class='text-info'>Processing...</strong>",
                            text: `Reactivating "${itemName}" in progress`,
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(function () {
                            // AJAX call to reactivate the user
                            $.ajax({
                                url: reactivateUrl,
                                type: "PUT",
                                headers: {
                                    "X-CSRF-TOKEN": csrf,
                                },
                                success: function (data) {
                                    if (data.success) {
                                        Swal.fire({
                                            title: "<strong class='text-success'>Reactivated Successfully</strong>",
                                            text: `"${itemName}" has been reactivated successfully.`,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        }).then(() => datatable.draw());
                                    } else {
                                        Swal.fire({
                                            title: "<strong class='text-danger'>Reactivate Failed</strong>",
                                            text: data.message || `Failed to reactivate "${itemName}".`,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-danger",
                                            },
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire({
                                        title: "<strong class='text-danger'>Error Occurred</strong>",
                                        text: `Something went wrong while reactivating "${itemName}".`,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "OK",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-danger",
                                        },
                                    });
                                },
                            });
                        });
                    }
                });
            });
        });
    };

    // toolbar visible button and count
    var toolbars = function () {
        const container = document.querySelector("#table-1");
        const toolbarBase = document.querySelector('[toolbar-table-1="base"]');
        const toolbarSelected = document.querySelector(
            '[toolbar-table-1="selected"]'
        );
        const selectedCount = document.querySelector(
            '[toolbar-table-1="count"]'
        );

        if (!container || !toolbarBase || !toolbarSelected || !selectedCount) {
            // console.error("Required elements not found for toolbar visibility");
            return;
        }

        const allCheckboxes = container.querySelectorAll(
            'tbody [type="checkbox"]'
        );

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach((c) => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add("d-none");
            toolbarSelected.classList.remove("d-none");
        } else {
            toolbarBase.classList.remove("d-none");
            toolbarSelected.classList.add("d-none");
        }
    };

    // checkbox toolbar
    var checkboxToolbar = function () {
        const container = document.querySelector("#table-1");
        const checkboxes = container.querySelectorAll('[type="checkbox"]');

        const deleteSelected = document.querySelector(
            '[action-select-table-1="delete"]'
        );

        // To store selected customer IDs
        let selectedIds = [];

        // Loop through each checkbox
        checkboxes.forEach((c) => {
            c.addEventListener("click", function () {
                setTimeout(function () {
                    toolbars(); // Update the toolbar visibility
                }, 50);
            });
        });

        deleteSelected.addEventListener("click", function () {
            // Get all checked checkboxes and extract their data-id values
            const selectedIds = Array.from(
                container.querySelectorAll(
                    'tbody input[type="checkbox"]:checked'
                )
            ).map((checkbox) => checkbox.dataset.id);

            // Exit if no items selected
            if (selectedIds.length === 0) {
                Swal.fire({
                    title: "No Selection",
                    text: `Please select at least one item to delete.`,
                    icon: "info",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    },
                });
                return;
            }

            Swal.fire({
                title: "Confirm Deletion",
                text: `Are you sure you want to delete the ${selectedIds.length} selected items?`,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "Cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
                preConfirm: function () {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: window.location.origin + "/users/bulk-destroy",
                            type: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrf,
                            },
                            data: {
                                ids: selectedIds,
                            },
                            success: function (data) {
                                if (data.success) {
                                    resolve(); // Resolves the promise if deletion is successful
                                } else {
                                    // console.error(
                                    //     "Error: ",
                                    //     data.message ||
                                    //         "Failed to delete selected items."
                                    // );
                                    reject(
                                        data.message ||
                                            "Failed to delete selected items."
                                    );
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("AJAX Error: ", error); // Log the actual error to the console
                                console.error("Response: ", xhr.responseText); // Log the server response if any
                                reject(
                                    `Something went wrong while deleting selected items.`
                                );
                            },
                        });
                    });
                },
            })
                .then(function (result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Deletion Successful",
                            text: `Successfully deleted ${selectedIds.length} items`,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        }).then(function () {
                            datatable.draw(); // Redraw table after deletion
                            // Optional: Clear selected IDs and reset checkboxes if needed
                            const headerCheckbox =
                                container.querySelectorAll(
                                    '[type="checkbox"]'
                                )[0];
                            headerCheckbox.checked = false;
                        });
                    }
                })
                .catch(function (error) {
                    // If the promise is rejected, show the error message
                    Swal.fire({
                        title: "Error",
                        text: error,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    });
                });
        });
    };

    return {
        init: function () {
            initDatatable();
            search();
            filter();
            reset();
            rows();
            checkboxToolbar();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    DataTableSideServer.init();
});
