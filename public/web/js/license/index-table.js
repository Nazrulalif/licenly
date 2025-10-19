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
            order: [[4, "desc"]],
            ajax: {
                url: window.location.origin + window.location.pathname,
                type: "GET",
                data: function (d) {
                    return d;
                },
                dataSrc: function (response) {
                    // console.log("DataTables Response:", response);
                    return response.data;
                },
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    searchable: false,
                    className: "text-center",
                },
                { data: "license_id", name: "license_id", searchable: true },
                { data: "customer_id", name: "customer_id", searchable: true },
                { data: "license_type", name: "license_type", searchable: false, orderable: true },
                { data: "status", name: "status", searchable: false, orderable: true },
                { data: "expiry_date", name: "expiry_date", searchable: false, orderable: true },
                {
                    data: "action",
                    name: "actions",
                    orderable: false,
                    searchable: false,
                    className: "text-end",
                },
            ],
            columnDefs: [
            ],
        });

        table = datatable.$;

        datatable.on("draw", function () {
            rows();
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


    // row function
    var rows = function () {
        // Select all view buttons
        const viewButtons = document.querySelectorAll(
            '[action-row-table-1="view"]'
        );

        // Select all activate buttons
        const activateButtons = document.querySelectorAll(
            '[action-row-table-1="activate"]'
        );

        // Select all delete buttons
        const deleteButtons = document.querySelectorAll(
            '[action-row-table-1="delete"]'
        );

        const downloadButtons = document.querySelectorAll('[action-row-table-1="download"]');

        const revokeButtons = document.querySelectorAll('[action-row-table-1="revoke"]');

        // Add click event listener to each view button
        viewButtons.forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                const keyId = btn.getAttribute("data-id");
                const keyName = btn.getAttribute("data-name");
                const keyStatus = btn.getAttribute("data-status");
                const keySize = btn.getAttribute("data-key-size");
                const keyGenerated = btn.getAttribute("data-generated");

                // Show loading state
                Swal.fire({
                    title: "<strong class='text-info'>Loading...</strong>",
                    text: "Fetching RSA key details",
                    icon: "info",
                    buttonsStyling: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                });

                // Fetch public key from server
                $.ajax({
                    url: window.location.origin + "/rsakey/" + keyId + "/public-key",
                    type: "GET",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                    },
                    success: function (data) {
                        Swal.close();

                        if (data.success) {
                            // Populate modal with data
                            $("#modal-key-name").text(keyName || "N/A");
                            $("#modal-key-size").text(keySize ? keySize + " bits" : "N/A");
                            $("#modal-key-date").text(keyGenerated || "N/A");
                            $("#modal-public-key").val(data.public_key || "");

                            // Set status badge
                            const statusHtml = keyStatus === "1"
                                ? '<span class="badge badge-light-success">Active</span>'
                                : '<span class="badge badge-light-secondary">Inactive</span>';
                            $("#modal-key-status").html(statusHtml);

                            // Store data for download
                            $("#download-public-key").data("public-key", data.public_key);
                            $("#download-public-key").data("key-name", keyName);

                            // Show modal
                            const modal = new bootstrap.Modal(document.getElementById("viewRsaKeyModal"));
                            modal.show();
                        } else {
                            Swal.fire({
                                title: "<strong class='text-danger'>Error</strong>",
                                text: data.message || "Failed to fetch key details",
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
                        Swal.close();
                        Swal.fire({
                            title: "<strong class='text-danger'>Error Occurred</strong>",
                            text: "Something went wrong while fetching key details.",
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
        });

        // Add click event listener to each activate button
        activateButtons.forEach(function (btn) {
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

                let activateUrl = targetElement.getAttribute("data-id");

                if (!activateUrl && targetElement.dataset && targetElement.dataset.id) {
                    activateUrl = targetElement.dataset.id;
                }

                if (!activateUrl) {
                    Swal.fire({
                        title: "<strong class='text-danger'>Error</strong>",
                        text: "Could not identify the activate URL. Please try again or contact support.",
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
                    title: "<strong class='text-success'>Activate Confirmation</strong>",
                    text: `Are you sure you want to activate "${itemName}"? This will deactivate all other keys.`,
                    icon: "question",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, activate it",
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
                            text: `Activating "${itemName}" in progress`,
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(function () {
                            // AJAX call to activate the key
                            $.ajax({
                                url: activateUrl,
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": csrf,
                                },
                                success: function (data) {
                                    if (data.success) {
                                        Swal.fire({
                                            title: "<strong class='text-success'>Activated Successfully</strong>",
                                            text: `"${itemName}" has been activated successfully.`,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        }).then(() => datatable.draw());
                                    } else {
                                        Swal.fire({
                                            title: "<strong class='text-danger'>Activate Failed</strong>",
                                            text: data.message || `Failed to activate "${itemName}".`,
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
                                        text: `Something went wrong while activating "${itemName}".`,
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

        // Add click event listener to each download button
        downloadButtons.forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                const downloadUrl = btn.getAttribute("data-id");
                if (downloadUrl) {
                    window.location.href = downloadUrl;
                }
            });
        });

        // Add click event listener to each revoke button
        revokeButtons.forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                let targetElement = event.target;
                while (targetElement && !targetElement.hasAttribute("action-row-table-1")) {
                    targetElement = targetElement.parentElement;
                }

                if (!targetElement) return;

                const tableRow = targetElement.closest("tr");
                const itemName = tableRow.querySelector("td:nth-child(2)").innerText.trim();
                let revokeUrl = targetElement.getAttribute("data-id");

                if (!revokeUrl) {
                    Swal.fire({
                        title: "<strong class='text-danger'>Error</strong>",
                        text: "Could not identify the revoke URL.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                        },
                    });
                    return;
                }

                Swal.fire({
                    title: "<strong class='text-warning'>Revoke Confirmation</strong>",
                    html: `
                        <p>Are you sure you want to revoke "${itemName}"?</p>
                        <textarea id="revocation_reason" class="form-control mt-3"
                            placeholder="Reason for revocation (optional)" rows="3"></textarea>
                    `,
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, revoke it",
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-warning",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                    preConfirm: () => {
                        return document.getElementById('revocation_reason').value;
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "<strong class='text-info'>Processing...</strong>",
                            text: `Revoking "${itemName}" in progress`,
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000,
                        }).then(function () {
                            $.ajax({
                                url: revokeUrl,
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": csrf,
                                },
                                data: {
                                    revocation_reason: result.value
                                },
                                success: function (data) {
                                    if (data.success) {
                                        Swal.fire({
                                            title: "<strong class='text-success'>Revoked Successfully</strong>",
                                            text: `"${itemName}" has been revoked successfully.`,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "OK",
                                            customClass: {
                                                confirmButton: "btn fw-bold btn-primary",
                                            },
                                        }).then(() => datatable.draw());
                                    } else {
                                        Swal.fire({
                                            title: "<strong class='text-danger'>Revoke Failed</strong>",
                                            text: data.message || `Failed to revoke "${itemName}".`,
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
                                        text: `Something went wrong while revoking "${itemName}".`,
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


    // Modal copy button handler
    var modalCopyButton = function () {
        $(document).on("click", "#copy-public-key", function () {
            const publicKey = $("#modal-public-key").val();

            if (publicKey) {
                // Create temporary textarea
                const tempTextarea = document.createElement("textarea");
                tempTextarea.value = publicKey;
                tempTextarea.style.position = "fixed";
                tempTextarea.style.opacity = "0";
                document.body.appendChild(tempTextarea);

                // Select and copy
                tempTextarea.select();
                tempTextarea.setSelectionRange(0, 99999);

                try {
                    document.execCommand("copy");

                    // Show success message
                    const originalHtml = $(this).html();
                    $(this).html('<i class="ki-duotone ki-check fs-3"><span class="path1"></span><span class="path2"></span></i> Copied!');

                    setTimeout(() => {
                        $("#copy-public-key").html(originalHtml);
                    }, 2000);
                } catch (err) {
                    Swal.fire({
                        title: "Copy Failed",
                        text: "Failed to copy to clipboard",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    });
                }

                document.body.removeChild(tempTextarea);
            }
        });
    };

    // Modal download button handler
    var modalDownloadButton = function () {
        $(document).on("click", "#download-public-key", function () {
            const publicKey = $(this).data("public-key");
            const keyName = $(this).data("key-name");

            if (publicKey) {
                const filename = (keyName || "rsa_key").replace(/\s+/g, "_") + ".pub";
                const blob = new Blob([publicKey], { type: "text/plain" });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

                // Show success message
                Swal.fire({
                    title: "<strong class='text-success'>Download Started</strong>",
                    text: `Public key file "${filename}" is being downloaded.`,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    timer: 2000,
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    },
                });
            }
        });
    };

    return {
        init: function () {
            initDatatable();
            search();
            rows();
            modalCopyButton();
            modalDownloadButton();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    DataTableSideServer.init();
});
