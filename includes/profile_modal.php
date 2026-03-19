<!-- Profile Modal -->
<div class="modal-overlay" id="profileModal">
    <div class="modal-box">
        <div class="modal-header">
            <h2>My Profile</h2>
            <button class="close-btn" onclick="closeProfileModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="profileForm" enctype="multipart/form-data" onsubmit="submitProfileUpdate(event)">
            <div class="form-group" style="text-align: center; margin-bottom: 1.5rem;">
                <?php
                    require_once BASE_PATH . '/app/models/User.php';
                    $user_mdl = new User($pdo);
                    $curr_user = $user_mdl->findById($_SESSION['user_id']);
                    $current_img = !empty($curr_user['profile_image']) ? ASSET_URL . '/images/profiles/' . $curr_user['profile_image'] : 'https://i.pravatar.cc/150?img=11';
                ?>
                <img id="prof_img_preview" src="<?= $current_img ?>" alt="Profile" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 0.5rem; border: 2px solid var(--border-color);">
                <br>
                <label for="prof_image" class="btn" style="display: inline-block; padding: 0.3rem 0.8rem; font-size: 0.8rem; background: var(--bg-color); border: 1px solid var(--border-color); cursor: pointer; color: var(--text-main);">Change Image</label>
                <input type="file" id="prof_image" name="profile_image" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
            </div>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" id="prof_name" name="name" class="form-control" required value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="prof_email" name="email" class="form-control" required value="<?= htmlspecialchars($curr_user['email'] ?? '') ?>">
            </div>

            <div class="form-group" style="margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <label style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 0.8rem;">Change Password (Leave blank to keep same)</label>
                <label>New Password</label>
                <input type="password" id="prof_password" name="password" class="form-control" placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" id="prof_confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password">
            </div>

            <div id="prof_error_msg" style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.5rem; text-align: center;"></div>

            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeProfileModal()" style="background-color: var(--surface-color); border: 1px solid var(--border-color); color: var(--text-main);">Cancel</button>
                <button type="submit" class="btn btn-primary" id="prof_submit_btn">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    const BASE_APP_URL = "<?= APP_URL ?>";

    function previewProfileImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('prof_img_preview');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function openProfileModal(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Hide dropdown
        const pDropdown = document.getElementById('adminProfileDropdown');
        if (pDropdown) pDropdown.style.display = 'none';

        const modal = document.getElementById('profileModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeProfileModal() {
        const modal = document.getElementById('profileModal');
        const msg = document.getElementById('prof_error_msg');
        if (msg) msg.innerText = '';
        if (modal) modal.style.display = 'none';
        
        // Clear password fields
        document.getElementById('prof_password').value = '';
        document.getElementById('prof_confirm_password').value = '';
    }

    // Handle click outside to close modal
    window.addEventListener('click', function(event) {
        const pModal = document.getElementById('profileModal');
        if (event.target == pModal) {
            closeProfileModal();
        }
    });

    async function submitProfileUpdate(e) {
        e.preventDefault();
        const btn = document.getElementById('prof_submit_btn');
        const msg = document.getElementById('prof_error_msg');
        
        btn.innerText = 'Updating...';
        btn.disabled = true;
        msg.innerText = '';
        msg.style.color = '#e74c3c';

        const name = document.getElementById('prof_name').value;
        const email = document.getElementById('prof_email').value;
        const password = document.getElementById('prof_password').value;
        const confirm_password = document.getElementById('prof_confirm_password').value;
        const imageFile = document.getElementById('prof_image').files[0];

        if (password !== confirm_password) {
            msg.innerText = 'Passwords do not match.';
            btn.innerText = 'Update Profile';
            btn.disabled = false;
            return;
        }

        try {
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('confirm_password', confirm_password);
            
            if (imageFile) {
                formData.append('profile_image', imageFile);
            }

            const response = await fetch(BASE_APP_URL + '/profile/update', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                msg.style.color = '#2ecc71';
                msg.innerText = result.message;
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                msg.innerText = result.message || 'Update failed.';
                btn.innerText = 'Update Profile';
                btn.disabled = false;
            }
        } catch (err) {
            console.error(err);
            msg.innerText = 'An error occurred. Please try again later.';
            btn.innerText = 'Update Profile';
            btn.disabled = false;
        }
    }
</script>

