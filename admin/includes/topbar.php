<!-- Topbar -->
    <div class="topbar">
        <div class="topbar-content">
		
			<div class="topbar-search">
                
            </div>
		
            <div class="topbar-actions">
                <button class="mobile-toggle" id="mobileToggle" title="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <!--<button class="topbar-icon-btn has-notification" title="Notifications">
                    <i class="fas fa-bell"></i>
                </button>
                <button class="topbar-icon-btn" title="Messages">
                    <i class="fas fa-envelope"></i>
                </button>-->
                <div class="topbar-profile">
                    <div class="profile-avatar">AS</div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.95rem;"><?php if(isset($name)){ echo $name; }?></div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Administrator</div>
                    </div>
                </div>
            </div>
        </div>
    </div>