<!-- Information Modal (Automatic Sliding / Auto-Carousel) -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered info-modal-dialog">
        <div class="modal-content info-modal-content">

            <!-- Modal Header -->
            <div class="modal-header info-modal-header">
                <div class="header-title-wrap">
                    <div class="header-icon-badge" aria-hidden="true">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="infoModalLabel">
                            Important Information
                        </h5>
                        <p class="header-subtitle">San Roque High School &bull; Academic Announcements &amp; Guidelines</p>
                    </div>
                </div>
                <button type="button" class="btn-close info-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Auto-Slide Progress Bar (Fills up smoothly every 5.5s) -->
            <div class="auto-progress-bar-wrap" title="Slide progression timer">
                <div class="auto-progress-bar" id="autoProgressBar"></div>
            </div>

            <!-- Slide Category Indicator Bar -->
            <div class="slide-indicator-bar">
                <button type="button" class="slide-nav-pill active" id="tabSlide1" onclick="manualJumpToSlide(0)">
                    <span class="pill-dot"></span>
                    <span class="pill-title"><i class="fas fa-calendar-alt me-1"></i> 1. Important Dates &amp; Deadlines</span>
                </button>
                <button type="button" class="slide-nav-pill" id="tabSlide2" onclick="manualJumpToSlide(1)">
                    <span class="pill-dot"></span>
                    <span class="pill-title"><i class="fas fa-file-alt me-1"></i> 2. Required Documents for Admission</span>
                </button>
            </div>

            <!-- Modal Body with Automatic Sliding Viewport (NO SCROLLING) -->
            <div class="modal-body info-modal-body p-0" id="infoModalBody">
                <div class="info-slider-viewport" id="infoSliderViewport">
                    <div class="info-slider-track" id="infoSliderTrack">

                        <!-- ==========================================
                             SLIDE 1: IMPORTANT DATES & DEADLINES
                             ========================================== -->
                        <div class="info-slide" id="slide1Panel">
                            <div class="info-slide-inner">

                                <div class="info-schedules-grid">
                                    <!-- Column 1: Admission & Enrollment Periods -->
                                    <div class="schedules-col">

                                        <!-- Admission Period Card -->
                                        <div class="info-card">
                                            <div class="info-card-header">
                                                <div class="card-icon-wrap icon-blue">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <div class="card-title-group">
                                                    <h6 class="info-card-title">Admission Period</h6>
                                                    <span class="info-card-desc">New students &amp; transferees</span>
                                                </div>
                                            </div>
                                            <div class="info-card-body">
                                                <div class="date-row">
                                                    <span class="date-label">
                                                        <i class="fas fa-play text-primary"></i> Start Date
                                                    </span>
                                                    <span class="date-val date-active" id="admission-start">April 1, 2026</span>
                                                </div>
                                                <div class="date-row">
                                                    <span class="date-label">
                                                        <i class="fas fa-stop text-muted"></i> End Date
                                                    </span>
                                                    <span class="date-val date-tba" id="admission-end">To be announced</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Enrollment Period Card -->
                                        <div class="info-card">
                                            <div class="info-card-header">
                                                <div class="card-icon-wrap icon-teal">
                                                    <i class="fas fa-clipboard-check"></i>
                                                </div>
                                                <div class="card-title-group">
                                                    <h6 class="info-card-title">Enrollment Period</h6>
                                                    <span class="info-card-desc">Approved applicants &amp; continuing</span>
                                                </div>
                                            </div>
                                            <div class="info-card-body">
                                                <div class="date-row">
                                                    <span class="date-label">
                                                        <i class="fas fa-play text-muted"></i> Start Date
                                                    </span>
                                                    <span class="date-val date-tba" id="enrollment-start">To be announced</span>
                                                </div>
                                                <div class="date-row">
                                                    <span class="date-label">
                                                        <i class="fas fa-stop text-muted"></i> End Date
                                                    </span>
                                                    <span class="date-val date-tba" id="enrollment-end">To be announced</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Column 2: Grade Input Deadlines -->
                                    <div class="schedules-col">
                                        <div class="info-card h-100 grade-deadlines-card">
                                            <div class="info-card-header">
                                                <div class="card-icon-wrap icon-purple">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                                <div class="card-title-group">
                                                    <h6 class="info-card-title">Grade Input Deadlines</h6>
                                                    <span class="info-card-desc">Faculty quarterly submission schedule</span>
                                                </div>
                                            </div>
                                            <div class="info-card-body">
                                                <div class="quarters-list">
                                                    <div class="quarter-item">
                                                        <div class="quarter-badge">Q1</div>
                                                        <span class="quarter-name">1st Quarter</span>
                                                        <span class="date-val date-tba ms-auto" id="grade-q1">To be announced</span>
                                                    </div>
                                                    <div class="quarter-item">
                                                        <div class="quarter-badge">Q2</div>
                                                        <span class="quarter-name">2nd Quarter</span>
                                                        <span class="date-val date-tba ms-auto" id="grade-q2">To be announced</span>
                                                    </div>
                                                    <div class="quarter-item">
                                                        <div class="quarter-badge">Q3</div>
                                                        <span class="quarter-name">3rd Quarter</span>
                                                        <span class="date-val date-tba ms-auto" id="grade-q3">To be announced</span>
                                                    </div>
                                                    <div class="quarter-item">
                                                        <div class="quarter-badge">Q4</div>
                                                        <span class="quarter-name">4th Quarter</span>
                                                        <span class="date-val date-tba ms-auto" id="grade-q4">To be announced</span>
                                                    </div>
                                                </div>
                                                <div class="shs-note">
                                                    <i class="fas fa-info-circle text-purple"></i>
                                                    <span><strong>Senior High School:</strong> Midterm and Final grades for each semester.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Auto-Slide Upcoming Notice Pill -->
                                <div class="auto-slide-hint">
                                    <i class="fas fa-sync-alt spin-slow"></i>
                                    <span>Automatically sliding to <strong>Required Documents</strong>... (hover to pause)</span>
                                </div>

                            </div>
                        </div>

                        <!-- ==========================================
                             SLIDE 2: REQUIRED DOCUMENTS FOR ADMISSION
                             ========================================== -->
                        <div class="info-slide" id="slide2Panel">
                            <div class="info-slide-inner">

                                <div class="docs-comparison-grid">

                                    <!-- New Students Card -->
                                    <div class="doc-card">
                                        <div class="doc-card-head">
                                            <div class="doc-badge new-student-badge">
                                                <i class="fas fa-user-plus"></i>
                                            </div>
                                            <div>
                                                <span class="doc-group-title">For New Students / Transferees</span>
                                                <small class="doc-group-sub">Incoming Grade 7 &amp; transfer applicants</small>
                                            </div>
                                        </div>
                                        <ul class="doc-checklist">
                                            <li>
                                                <i class="fas fa-check-circle doc-check"></i>
                                                <span>Form 137 <small class="text-muted">(Transfer Credential)</small></span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check"></i>
                                                <span>Report Card <small class="text-muted">(Form 138)</small></span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check"></i>
                                                <span>Birth Certificate <small class="text-muted">(PSA Authenticated)</small></span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check-opt"></i>
                                                <span>Good Moral Certificate</span>
                                                <span class="badge-opt">Optional</span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check-opt"></i>
                                                <span>Medical Certificate</span>
                                                <span class="badge-opt">Optional</span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check-opt"></i>
                                                <span>2x2 ID Picture</span>
                                                <span class="badge-opt">Optional</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Old Students Card -->
                                    <div class="doc-card">
                                        <div class="doc-card-head">
                                            <div class="doc-badge old-student-badge">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                            <div>
                                                <span class="doc-group-title">For Old Students / Continuing</span>
                                                <small class="doc-group-sub">Current San Roque High School students</small>
                                            </div>
                                        </div>
                                        <ul class="doc-checklist">
                                            <li>
                                                <i class="fas fa-check-circle doc-check"></i>
                                                <span>Report Card <small class="text-muted">(Form 138 with final rating)</small></span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check-opt"></i>
                                                <span>Medical Certificate</span>
                                                <span class="badge-opt">Optional</span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check-opt"></i>
                                                <span>2x2 ID Picture</span>
                                                <span class="badge-opt">Optional</span>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle doc-check-opt"></i>
                                                <span>Validated School ID</span>
                                                <span class="badge-opt">Optional</span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                                <!-- Guidelines Callout Banner -->
                                <div class="doc-guidelines-banner">
                                    <i class="fas fa-info-circle banner-icon"></i>
                                    <div>
                                        <strong>Document Guidelines:</strong>
                                        All files must be in PDF or clear image format (JPG, PNG). Maximum file size is <strong>2MB</strong> per document.
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer info-modal-footer">
                <div class="footer-left">
                    <!-- Live Auto-sliding status pill -->
                    <div class="auto-status-pill" id="autoStatusPill" title="Slides automatically every 5.5 seconds">
                        <span class="pulse-indicator" id="pulseDot"></span>
                        <span class="status-label" id="statusLabelText">Auto-sliding</span>
                    </div>

                    <!-- Slide dots -->
                    <div class="slide-dots">
                        <button type="button" class="dot active" id="dotSlide0" title="Slide 1" onclick="manualJumpToSlide(0)"></button>
                        <button type="button" class="dot" id="dotSlide1" title="Slide 2" onclick="manualJumpToSlide(1)"></button>
                    </div>
                </div>

                <div class="footer-actions">
                    <!-- Direct Apply Button -->
                    <a href="{{ route('admission.create.public') }}" class="btn btn-apply-action">
                        <i class="fas fa-file-signature me-1"></i> Apply for Admission
                    </a>

                    <!-- Close Button -->
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
       MODAL DIALOG & CONTAINER
       ========================================================= */
    #infoModal .info-modal-dialog {
        max-width: 860px;
        width: min(94vw, 860px);
        margin: 1.5rem auto;
    }

    #infoModal .info-modal-content {
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 20px 45px -10px rgba(15, 28, 46, 0.25), 0 2px 6px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #ffffff;
    }

    /* Modal entrance animation */
    #infoModal.fade .modal-dialog {
        transform: translate(0, -20px);
        transition: transform 0.3s ease-out;
    }
    #infoModal.show .modal-dialog {
        transform: translate(0, 0);
    }

    /* =========================================================
       MODAL HEADER
       ========================================================= */
    #infoModal .info-modal-header {
        background: linear-gradient(135deg, #1a56b0 0%, #153e80 60%, #2e388f 100%);
        color: #ffffff;
        padding: 16px 22px;
        border-bottom: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #infoModal .header-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    #infoModal .header-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(4px);
        display: grid;
        place-items: center;
        font-size: 17px;
        color: #ffffff;
        flex-shrink: 0;
    }

    #infoModal .modal-title {
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: -0.3px;
        color: #ffffff;
        margin: 0;
        line-height: 1.25;
    }

    #infoModal .header-subtitle {
        font-size: 11.5px;
        color: rgba(255, 255, 255, 0.8);
        margin: 3px 0 0;
        letter-spacing: 0.2px;
    }

    #infoModal .info-modal-close {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
        opacity: 0.9;
        filter: invert(1) grayscale(100%) brightness(200%);
        transition: all 0.2s ease;
        padding: 0;
        margin: 0;
    }

    #infoModal .info-modal-close:hover {
        opacity: 1;
        background-color: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    /* =========================================================
       AUTO-SLIDE PROGRESS BAR
       ========================================================= */
    #infoModal .auto-progress-bar-wrap {
        width: 100%;
        height: 3px;
        background: #e2e8f0;
        position: relative;
        overflow: hidden;
    }

    #infoModal .auto-progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        transition: width 0.1s linear;
    }

    /* =========================================================
       SLIDE INDICATOR BAR (TOP)
       ========================================================= */
    #infoModal .slide-indicator-bar {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
        padding: 6px 12px;
        gap: 8px;
    }

    #infoModal .slide-nav-pill {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.25s ease;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
    }

    #infoModal .slide-nav-pill:hover {
        background: rgba(255, 255, 255, 0.6);
        color: #1e293b;
    }

    #infoModal .slide-nav-pill.active {
        background: #ffffff;
        color: #1a56b0;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
    }

    #infoModal .slide-nav-pill .pill-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    #infoModal .slide-nav-pill.active .pill-dot {
        background: #1a56b0;
        transform: scale(1.25);
    }

    /* =========================================================
       SLIDER VIEWPORT & TRACK (NO SCROLLING)
       ========================================================= */
    #infoModal .info-modal-body {
        overflow: hidden !important;
        position: relative;
        background: #f8fafc;
    }

    #infoModal .info-slider-viewport {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    #infoModal .info-slider-track {
        display: flex;
        width: 200%;
        transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }

    #infoModal .info-slide {
        width: 50%;
        flex-shrink: 0;
        padding: 18px 22px 14px;
        box-sizing: border-box;
    }

    #infoModal .info-slide-inner {
        width: 100%;
    }

    /* =========================================================
       SLIDE 1: SCHEDULES 2-COLUMN GRID
       ========================================================= */
    #infoModal .info-schedules-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        align-items: stretch;
    }

    #infoModal .schedules-col {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Cards */
    #infoModal .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 13px 15px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    #infoModal .info-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(26, 86, 176, 0.08);
        transform: translateY(-1px);
    }

    #infoModal .info-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    #infoModal .card-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: grid;
        place-items: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    #infoModal .card-icon-wrap.icon-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    #infoModal .card-icon-wrap.icon-teal {
        background: #ecfdf5;
        color: #059669;
    }

    #infoModal .card-icon-wrap.icon-purple {
        background: #f5f3ff;
        color: #7c3aed;
    }

    #infoModal .card-title-group {
        min-width: 0;
    }

    #infoModal .info-card-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.25;
    }

    #infoModal .info-card-desc {
        font-size: 11px;
        color: #64748b;
        display: block;
        margin-top: 1px;
    }

    #infoModal .info-card-body {
        display: flex;
        flex-direction: column;
        gap: 7px;
        flex: 1;
    }

    /* Date Rows */
    #infoModal .date-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 5px 8px;
        background: #f8fafc;
        border-radius: 8px;
        font-size: 12px;
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
        font-size: 11.5px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 6px;
        white-space: nowrap;
    }

    #infoModal .date-val.date-active {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    #infoModal .date-val.date-tba {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        font-weight: 500;
    }

    /* Quarters List */
    #infoModal .quarters-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    #infoModal .quarter-item {
        display: flex;
        align-items: center;
        padding: 4px 8px;
        background: #f8fafc;
        border-radius: 8px;
        font-size: 12px;
    }

    #infoModal .quarter-badge {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #ede9fe;
        color: #6d28d9;
        font-weight: 700;
        font-size: 10px;
        display: grid;
        place-items: center;
        margin-right: 8px;
        flex-shrink: 0;
    }

    #infoModal .quarter-name {
        font-weight: 600;
        color: #334155;
    }

    #infoModal .shs-note {
        font-size: 11px;
        color: #581c87;
        background: #faf5ff;
        border: 1px solid #f3e8ff;
        padding: 6px 9px;
        border-radius: 8px;
        margin-top: auto;
        display: flex;
        align-items: flex-start;
        gap: 6px;
        line-height: 1.4;
    }

    #infoModal .shs-note i {
        margin-top: 2px;
        flex-shrink: 0;
        color: #9333ea;
    }

    /* Slide 1 auto hint */
    #infoModal .auto-slide-hint {
        margin-top: 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        color: #64748b;
    }

    #infoModal .spin-slow {
        color: #3b82f6;
        animation: spinSlow 8s linear infinite;
    }

    @keyframes spinSlow {
        to { transform: rotate(360deg); }
    }

    /* =========================================================
       SLIDE 2: REQUIRED DOCUMENTS
       ========================================================= */
    #infoModal .docs-comparison-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    #infoModal .doc-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    #infoModal .doc-card-head {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 9px;
        padding-bottom: 7px;
        border-bottom: 1px solid #f1f5f9;
    }

    #infoModal .doc-badge {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: grid;
        place-items: center;
        font-size: 11.5px;
        flex-shrink: 0;
    }

    #infoModal .new-student-badge {
        background: #dbeafe;
        color: #1d4ed8;
    }

    #infoModal .old-student-badge {
        background: #dcfce7;
        color: #15803d;
    }

    #infoModal .doc-group-title {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        display: block;
        line-height: 1.2;
    }

    #infoModal .doc-group-sub {
        font-size: 10px;
        color: #64748b;
        display: block;
    }

    #infoModal .doc-checklist {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    #infoModal .doc-checklist li {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        color: #334155;
        line-height: 1.35;
    }

    #infoModal .doc-check {
        color: #16a34a;
        font-size: 12.5px;
        flex-shrink: 0;
    }

    #infoModal .doc-check-opt {
        color: #94a3b8;
        font-size: 12.5px;
        flex-shrink: 0;
    }

    #infoModal .badge-opt {
        font-size: 9.5px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 1px 5px;
        border-radius: 4px;
        margin-left: auto;
    }

    /* Guidelines Banner */
    #infoModal .doc-guidelines-banner {
        margin-top: 10px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 11.5px;
        color: #1e40af;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.45;
    }

    #infoModal .doc-guidelines-banner .banner-icon {
        font-size: 13px;
        color: #2563eb;
        flex-shrink: 0;
    }

    /* =========================================================
       MODAL FOOTER
       ========================================================= */
    #infoModal .info-modal-footer {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 10px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #infoModal .footer-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    /* Pulsing status pill */
    #infoModal .auto-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 3px 9px;
        font-size: 11px;
        font-weight: 600;
        color: #1d4ed8;
    }

    #infoModal .pulse-indicator {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #2563eb;
        box-shadow: 0 0 0 rgba(37, 99, 235, 0.4);
        animation: pulseAnimation 1.6s infinite;
    }

    @keyframes pulseAnimation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(37, 99, 235, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
    }

    #infoModal .slide-dots {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    #infoModal .slide-dots .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    #infoModal .slide-dots .dot.active {
        width: 22px;
        border-radius: 10px;
        background: #1a56b0;
    }

    #infoModal .footer-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #infoModal .btn-apply-action {
        background: #1a56b0;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 2px 5px rgba(26, 86, 176, 0.2);
    }

    #infoModal .btn-apply-action:hover {
        background: #154690;
        color: #ffffff;
    }

    #infoModal .btn-dismiss-action {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 14px;
        transition: all 0.15s ease;
    }

    #infoModal .btn-dismiss-action:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    /* =========================================================
       RESPONSIVE BREAKPOINTS
       ========================================================= */
    @media (max-width: 768px) {
        #infoModal .info-modal-dialog {
            width: calc(100vw - 16px);
            max-width: 500px;
            margin: 0.6rem auto;
        }

        #infoModal .info-modal-header {
            padding: 12px 14px;
        }

        #infoModal .slide-indicator-bar {
            padding: 4px 6px;
        }

        #infoModal .slide-nav-pill {
            padding: 5px 8px;
            font-size: 10.5px;
        }

        #infoModal .info-slide {
            padding: 12px 14px;
        }

        #infoModal .info-schedules-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        #infoModal .docs-comparison-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        #infoModal .info-modal-footer {
            flex-direction: column;
            gap: 8px;
            padding: 10px 14px;
        }

        #infoModal .footer-left {
            width: 100%;
            justify-content: center;
        }

        #infoModal .footer-actions {
            width: 100%;
            justify-content: stretch;
            display: flex;
        }

        #infoModal .footer-actions .btn {
            flex: 1;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<script>
    (function () {
        let currentInfoSlide = 0;
        const totalInfoSlides = 2;
        const slideDurationMs = 5500; // 5.5 seconds per slide
        const tickIntervalMs = 50;
        let progressElapsed = 0;
        let autoSlideTimer = null;
        let isPaused = false;

        function updateSlideUI(slideIndex) {
            currentInfoSlide = slideIndex;

            // Slide track smoothly (hardware-accelerated translate)
            const track = document.getElementById('infoSliderTrack');
            if (track) {
                track.style.transform = `translateX(-${slideIndex * 50}%)`;
            }

            // Update top indicator pills
            const tab1 = document.getElementById('tabSlide1');
            const tab2 = document.getElementById('tabSlide2');
            if (tab1 && tab2) {
                tab1.classList.toggle('active', slideIndex === 0);
                tab2.classList.toggle('active', slideIndex === 1);
            }

            // Update footer dots
            const dot0 = document.getElementById('dotSlide0');
            const dot1 = document.getElementById('dotSlide1');
            if (dot0 && dot1) {
                dot0.classList.toggle('active', slideIndex === 0);
                dot1.classList.toggle('active', slideIndex === 1);
            }
        }

        function resetProgressBar() {
            progressElapsed = 0;
            const bar = document.getElementById('autoProgressBar');
            if (bar) {
                bar.style.width = '0%';
            }
        }

        function stepAutoSlide() {
            if (isPaused) return;

            progressElapsed += tickIntervalMs;
            const percent = Math.min((progressElapsed / slideDurationMs) * 100, 100);

            const bar = document.getElementById('autoProgressBar');
            if (bar) {
                bar.style.width = percent + '%';
            }

            if (progressElapsed >= slideDurationMs) {
                // Advance to next slide automatically
                const nextSlide = (currentInfoSlide + 1) % totalInfoSlides;
                updateSlideUI(nextSlide);
                resetProgressBar();
            }
        }

        function startAutoSlide() {
            stopAutoSlide();
            isPaused = false;
            resetProgressBar();
            autoSlideTimer = setInterval(stepAutoSlide, tickIntervalMs);
            updateStatusText('Auto-sliding');
        }

        function stopAutoSlide() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        }

        function pauseAutoSlide() {
            isPaused = true;
            updateStatusText('Paused');
            const pulse = document.getElementById('pulseDot');
            if (pulse) pulse.style.animationPlayState = 'paused';
        }

        function resumeAutoSlide() {
            isPaused = false;
            updateStatusText('Auto-sliding');
            const pulse = document.getElementById('pulseDot');
            if (pulse) pulse.style.animationPlayState = 'running';
        }

        function updateStatusText(text) {
            const label = document.getElementById('statusLabelText');
            if (label) label.textContent = text;
        }

        // Global function for manual clicking on dots or pills
        window.manualJumpToSlide = function (slideIndex) {
            updateSlideUI(slideIndex);
            resetProgressBar();
        };

        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('infoModal');
            if (!modalEl) return;

            // Pause on hover so users can comfortably read
            const modalContent = modalEl.querySelector('.info-modal-content');
            if (modalContent) {
                modalContent.addEventListener('mouseenter', pauseAutoSlide);
                modalContent.addEventListener('mouseleave', resumeAutoSlide);
            }

            // Touch gestures for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            const viewport = document.getElementById('infoSliderViewport');
            if (viewport) {
                viewport.addEventListener('touchstart', function (e) {
                    pauseAutoSlide();
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                viewport.addEventListener('touchend', function (e) {
                    touchEndX = e.changedTouches[0].screenX;
                    const diff = touchEndX - touchStartX;
                    if (Math.abs(diff) > 40) {
                        if (diff < 0) {
                            manualJumpToSlide((currentInfoSlide + 1) % totalInfoSlides);
                        } else {
                            manualJumpToSlide((currentInfoSlide - 1 + totalInfoSlides) % totalInfoSlides);
                        }
                    }
                    setTimeout(resumeAutoSlide, 1000);
                }, { passive: true });
            }

            // Start auto-slide when modal is shown
            modalEl.addEventListener('shown.bs.modal', function () {
                updateSlideUI(0);
                startAutoSlide();
            });

            // Stop auto-slide when modal is closed
            modalEl.addEventListener('hidden.bs.modal', function () {
                stopAutoSlide();
            });

            // If modal is already shown on initial load
            if (modalEl.classList.contains('show')) {
                updateSlideUI(0);
                startAutoSlide();
            }
        });
    })();
</script>
