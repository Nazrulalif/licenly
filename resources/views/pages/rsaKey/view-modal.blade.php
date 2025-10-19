    <!-- View RSA Key Modal -->
    <div class="modal fade" id="viewRsaKeyModal" tabindex="-1" aria-labelledby="viewRsaKeyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg  modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRsaKeyModalLabel">
                        <i class="ki-duotone ki-key fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        RSA Key Details
                    </h5>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Key Name:</label>
                        <div class="fs-6" id="modal-key-name"></div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-bold">Status:</label>
                        <div id="modal-key-status"></div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-bold">Key Size:</label>
                        <div class="fs-6" id="modal-key-size"></div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-bold">Generated Date:</label>
                        <div class="fs-6" id="modal-key-date"></div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-bold">Public Key:</label>
                        <div class="position-relative">
                            <textarea class="form-control form-control-solid" id="modal-public-key" rows="10" readonly></textarea>
                            <button type="button"
                                class="btn btn-sm btn-light-primary position-absolute top-0 end-0 mt-2 me-2"
                                id="copy-public-key">
                                <i class="ki-duotone ki-copy fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="download-public-key">
                        <i class="ki-duotone ki-file-down fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Download Public Key
                    </button>
                </div>
            </div>
        </div>
    </div>
