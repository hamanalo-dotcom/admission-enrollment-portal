<!-- Important Information Modal (Single Scrollable View) -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable info-modal-dialog">
        <div class="modal-content info-modal-content">

            <!-- Modal Header -->
            <div class="modal-header info-modal-header">
                <div class="header-title-wrap">
                    <div class="header-icon-badge" aria-hidden="true">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="infoModalLabel">Important Information</h5>
                        <p class="header-subtitle">San Roque High School &bull; Admission &amp; Enrollment Guidelines</p>
                    </div>
                </div>
                <button type="button" class="btn-close info-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Scrollable Modal Body -->
            <div class="modal-body info-modal-body" id="infoModalBody">

                <!-- 1. Admission and Enrollment Schedule -->
                <section class="info-section" aria-labelledby="scheduleHeading">
                    <div class="info-section-head">
                        <span class="info-section-num">1</span>
                        <div>
                            <h6 class="info-section-title" id="scheduleHeading">Admission and Enrollment Schedule</h6>
                            <p class="info-section-sub">Important dates for incoming applicants and continuing students</p>
                        </div>
                    </div>

                    <div class="info-grid-2">
                        <!-- Admission Period -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="card-icon-wrap icon-blue" aria-hidden="true">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="card-title-group">
                                    <h6 class="info-card-title">Admission Period</h6>
                                    <span class="info-card-desc">Incoming Grade 7, Grade 11 &amp; transferees</span>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <div class="date-row">
                                    <span class="date-label">
                                        <i class="fas fa-play text-primary" aria-hidden="true"></i> Start Date
                                    </span>
                                    <span class="date-val date-active" id="admission-start">April 1, 2026</span>
                                </div>
                                <div class="date-row">
                                    <span class="date-label">
                                        <i class="fas fa-stop text-muted" aria-hidden="true"></i> End Date
                                    </span>
                                    <span class="date-val date-tba" id="admission-end">To be announced</span>
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Period -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="card-icon-wrap icon-teal" aria-hidden="true">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div class="card-title-group">
                                    <h6 class="info-card-title">Enrollment Period</h6>
                                    <span class="info-card-desc">Approved applicants &amp; continuing students</span>
                                </div>
                            </div>
                            <div class="info-card-body">
                                <div class="date-row">
                                    <span class="date-label">
                                        <i class="fas fa-play text-muted" aria-hidden="true"></i> Start Date
                                    </span>
                                    <span class="date-val date-tba" id="enrollment-start">To be announced</span>
                                </div>
                                <div class="date-row">
                                    <span class="date-label">
                                        <i class="fas fa-stop text-muted" aria-hidden="true"></i> End Date
                                    </span>
                                    <span class="date-val date-tba" id="enrollment-end">To be announced</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 2 & 3. Required Documents -->
                <section class="info-section" aria-labelledby="documentsHeading">
                    <div class="info-section-head">
                        <span class="info-section-num">2</span>
                        <div>
                            <h6 class="info-section-title" id="documentsHeading">Required Documents for Admission</h6>
                            <p class="info-section-sub">Prepare clear digital copies before filling out the online application</p>
                        </div>
                    </div>

                    <div class="info-grid-2">
                        <!-- 2. Incoming Grade 7 and Grade 11 -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="card-icon-wrap icon-blue" aria-hidden="true">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="card-title-group">
                                    <h6 class="info-card-title">Incoming Grade 7 and Grade 11</h6>
                                    <span class="info-card-desc">New Junior High &amp; Senior High applicants</span>
                                </div>
                            </div>
                            <ul class="doc-checklist">
                                <li>
                                    <i class="fas fa-check-circle doc-check" aria-hidden="true"></i>
                                    <span class="doc-text">Form 137 <small class="text-muted">(Permanent School Record)</small></span>
                                    <span class="badge-req">Required</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle doc-check" aria-hidden="true"></i>
                                    <span class="doc-text">Report Card <small class="text-muted">(Form 138)</small></span>
                                    <span class="badge-req">Required</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle doc-check" aria-hidden="true"></i>
                                    <span class="doc-text">Birth Certificate <small class="text-muted">(PSA Authenticated)</small></span>
                                    <span class="badge-req">Required</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle-dot doc-check-opt" aria-hidden="true"></i>
                                    <span class="doc-text">Good Moral Certificate</span>
                                    <span class="badge-opt">Optional</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle-dot doc-check-opt" aria-hidden="true"></i>
                                    <span class="doc-text">Medical Certificate</span>
                                    <span class="badge-opt">Optional</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle-dot doc-check-opt" aria-hidden="true"></i>
                                    <span class="doc-text">2x2 ID Picture</span>
                                    <span class="badge-opt">Optional</span>
                                </li>
                            </ul>
                        </div>

                        <!-- 3. Transferees -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="card-icon-wrap icon-teal" aria-hidden="true">
                                    <i class="fas fa-right-left"></i>
                                </div>
                                <div class="card-title-group">
                                    <h6 class="info-card-title">Transferees</h6>
                                    <span class="info-card-desc">Applicants transferring from another school (Grades 7&ndash;12)</span>
                                </div>
                            </div>
                            <ul class="doc-checklist">
                                <li>
                                    <i class="fas fa-check-circle doc-check" aria-hidden="true"></i>
                                    <span class="doc-text">Form 137 <small class="text-muted">(Transfer Credential)</small></span>
                                    <span class="badge-req">Required</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle doc-check" aria-hidden="true"></i>
                                    <span class="doc-text">Report Card <small class="text-muted">(Form 138)</small></span>
                                    <span class="badge-req">Required</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle doc-check" aria-hidden="true"></i>
                                    <span class="doc-text">Birth Certificate <small class="text-muted">(PSA Authenticated)</small></span>
                                    <span class="badge-req">Required</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle-dot doc-check-opt" aria-hidden="true"></i>
                                    <span class="doc-text">Good Moral Certificate</span>
                                    <span class="badge-opt">Optional</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle-dot doc-check-opt" aria-hidden="true"></i>
                                    <span class="doc-text">Medical Certificate</span>
                                    <span class="badge-opt">Optional</span>
                                </li>
                                <li>
                                    <i class="fas fa-circle-dot doc-check-opt" aria-hidden="true"></i>
                                    <span class="doc-text">2x2 ID Picture</span>
                                    <span class="badge-opt">Optional</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="doc-guidelines-banner">
                        <i class="fas fa-info-circle banner-icon" aria-hidden="true"></i>
                        <div>
                            <strong>File Guidelines:</strong>
                            Accepted formats are <strong>PDF, JPG, JPEG, and PNG</strong>. Maximum file size is <strong>2MB</strong> per document.
                        </div>
                    </div>
                </section>

            </div>

            <!-- Modal Footer (Pinned Apply Action) -->
            <div class="modal-footer info-modal-footer">
                <span class="footer-note">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i> Ready to submit your application?
                </span>
                <div class="footer-actions">
                    <a href="{{ route('admission.create.public') }}" class="btn btn-apply-action">
                        <i class="fas fa-file-signature" aria-hidden="true"></i> Apply for Admission
                    </a>
                    <button type="button" class="btn btn-dismiss-action" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* =========================================================
       DIALOG & CONTAINER
       ========================================================= */
    #infoModal .info-modal-dialog {
        max-width: 860px;
        width: min(94vw, 860px);
        margin: 1rem auto;
    }

    #infoModal .info-modal-content {
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        box-shadow: 0 20px 45px -10px rgba(15, 28, 46, 0.25), 0 2px 6px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #ffffff;
        max-height: calc(100vh - 2rem);
        max-height: calc(100dvh - 2rem);
    }

    /* =========================================================
       HEADER
       ========================================================= */
    #infoModal .info-modal-header {
        background: linear-gradient(135deg, #1a56b0 0%, #143f87 100%);
        color: #ffffff;
        padding: 16px 22px;
        border-bottom: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-shrink: 0;
    }

    #infoModal .header-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    #infoModal .header-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        display: grid;
        place-items: center;
        font-size: 17px;
        color: #ffffff;
        flex-shrink: 0;
    }

    #infoModal .modal-title {
        font-size: 1.125rem;
        font-weight: 700;
        letter-spacing: -0.2px;
        color: #ffffff;
        margin: 0;
        line-height: 1.25;
    }

    #infoModal .header-subtitle {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.84);
        margin: 2px 0 0;
    }

    #infoModal .info-modal-close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
        opacity: 0.9;
        filter: invert(1) grayscale(100%) brightness(200%);
        transition: background-color 0.2s ease, opacity 0.2s ease;
        padding: 0;
        margin: 0;
        flex-shrink: 0;
    }

    #infoModal .info-modal-close:hover {
        opacity: 1;
        background-color: rgba(255, 255, 255, 0.3);
    }

    /* =========================================================
       SCROLLABLE BODY
       ========================================================= */
    #infoModal .info-modal-body {
        background: #f4f7fb;
        padding: 20px 22px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        -webkit-overflow-scrolling: touch;
    }

    #infoModal .info-section {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    #infoModal .info-section-head {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #infoModal .info-section-num {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: #1a56b0;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    #infoModal .info-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f1c2e;
        margin: 0;
        line-height: 1.3;
    }

    #infoModal .info-section-sub {
        font-size: 12px;
        color: #5a6a7e;
        margin: 1px 0 0;
        line-height: 1.4;
    }

    #infoModal .info-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        align-items: stretch;
    }

    /* =========================================================
       CARDS
       ========================================================= */
    #infoModal .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px 16px;
        box-shadow: 0 1px 3px rgba(15, 28, 46, 0.04);
        display: flex;
        flex-direction: column;
    }

    #infoModal .info-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    #infoModal .card-icon-wrap {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: grid;
        place-items: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    #infoModal .card-icon-wrap.icon-blue {
        background: #e8f0fc;
        color: #1a56b0;
    }

    #infoModal .card-icon-wrap.icon-teal {
        background: #ecfdf5;
        color: #059669;
    }

    #infoModal .card-title-group {
        min-width: 0;
    }

    #infoModal .info-card-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f1c2e;
        margin: 0;
        line-height: 1.3;
    }

    #infoModal .info-card-desc {
        font-size: 11.5px;
        color: #5a6a7e;
        display: block;
        margin-top: 2px;
        line-height: 1.35;
    }

    #infoModal .info-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Schedule Date Rows */
    #infoModal .date-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 7px 10px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        font-size: 12.5px;
    }

    #infoModal .date-label {
        color: #475569;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    #infoModal .date-label i {
        font-size: 9px;
    }

    #infoModal .date-val {
        font-size: 12px;
        font-weight: 600;
        padding: 2px 9px;
        border-radius: 6px;
        white-space: nowrap;
    }

    #infoModal .date-val.date-active {
        background: #e8f0fc;
        color: #1a56b0;
        border: 1px solid #bfdbfe;
    }

    #infoModal .date-val.date-tba {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        font-weight: 500;
    }

    /* Document Checklist */
    #infoModal .doc-checklist {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    #infoModal .doc-checklist li {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #334155;
        line-height: 1.4;
    }

    #infoModal .doc-text {
        flex: 1;
        min-width: 0;
    }

    #infoModal .doc-check {
        color: #16a34a;
        font-size: 13px;
        flex-shrink: 0;
    }

    #infoModal .doc-check-opt {
        color: #94a3b8;
        font-size: 11px;
        width: 13px;
        text-align: center;
        flex-shrink: 0;
    }

    #infoModal .badge-req {
        font-size: 10px;
        font-weight: 600;
        color: #15803d;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 1px 6px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    #infoModal .badge-opt {
        font-size: 10px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 1px 6px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    /* Guidelines Banner */
    #infoModal .doc-guidelines-banner {
        background: #e8f0fc;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12px;
        color: #143f87;
        display: flex;
        align-items: center;
        gap: 9px;
        line-height: 1.45;
    }

    #infoModal .doc-guidelines-banner .banner-icon {
        font-size: 14px;
        color: #1a56b0;
        flex-shrink: 0;
    }

    /* =========================================================
       FOOTER
       ========================================================= */
    #infoModal .info-modal-footer {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 12px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-shrink: 0;
    }

    #infoModal .footer-note {
        font-size: 12px;
        color: #5a6a7e;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    #infoModal .footer-note i {
        color: #1a56b0;
    }

    #infoModal .footer-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }

    #infoModal .btn-apply-action {
        background: #1a56b0;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: background-color 0.15s ease, transform 0.15s ease;
        box-shadow: 0 2px 6px rgba(26, 86, 176, 0.22);
    }

    #infoModal .btn-apply-action:hover {
        background: #143f87;
        color: #ffffff;
        transform: translateY(-1px);
    }

    #infoModal .btn-dismiss-action {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        transition: background-color 0.15s ease;
    }

    #infoModal .btn-dismiss-action:hover {
        background: #e2e8f0;
        color: #0f1c2e;
    }

    /* =========================================================
       RESPONSIVE BREAKPOINTS
       ========================================================= */
    @media (max-width: 768px) {
        #infoModal .info-modal-dialog {
            width: calc(100vw - 16px);
            margin: 0.5rem auto;
        }

        #infoModal .info-modal-content {
            max-height: calc(100vh - 1rem);
            max-height: calc(100dvh - 1rem);
        }

        #infoModal .info-modal-header {
            padding: 14px 16px;
        }

        #infoModal .info-modal-body {
            padding: 16px;
            gap: 16px;
        }

        #infoModal .info-grid-2 {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        #infoModal .info-modal-footer {
            padding: 12px 16px;
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }

        #infoModal .footer-note {
            display: none;
        }

        #infoModal .footer-actions {
            width: 100%;
            margin-left: 0;
        }

        #infoModal .footer-actions .btn {
            flex: 1;
            justify-content: center;
        }
    }

    @media (max-width: 420px) {
        #infoModal .modal-title {
            font-size: 1rem;
        }

        #infoModal .header-subtitle {
            font-size: 11px;
        }

        #infoModal .date-row {
            flex-wrap: wrap;
        }

        #infoModal .footer-actions {
            flex-direction: column;
        }

        #infoModal .footer-actions .btn {
            width: 100%;
        }
    }
</style>
