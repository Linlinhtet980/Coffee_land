<!-- Item Form Modal (Add/Edit) ကို ပြသပေးမည့် Popup Box အပိုင်း -->
  <div class="modal-overlay" id="itemModal">
      <div class="modal-box">
          <div class="modal-header">
              <h2 id="modalTitle">Add New Item</h2>
              <button class="close-btn" onclick="closeItemModal()"><i class="fas fa-times"></i></button>
          </div>
          <form action="<?= APP_URL ?>/admin/items" method="POST" id="itemForm">
              <input type="hidden" name="action" value="save">
              <input type="hidden" name="item_id" id="form_item_id" value="">
              
              <div class="form-group">
                  <label for="form_name">Item Name</label>
                  <input type="text" id="form_name" name="name" class="form-control" required>
              </div>

              <div class="form-row" style="display:flex; gap:16px;">
                  <div class="form-group" style="flex:1;">
                      <label for="form_cate_id">Category</label>
                      <select id="form_cate_id" name="cate_id" class="form-control">
                          <option value="">-- No Category --</option>
                          <?php foreach($categoriesList as $cat): ?>
                              <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <div class="form-group" style="flex:1;">
                      <label for="form_event_id">Event / Promotion</label>
                      <select id="form_event_id" name="event_id" class="form-control">
                          <option value="">-- No Event --</option>
                          <?php foreach($eventsList as $evt): ?>
                              <option value="<?= $evt['id'] ?>"><?= htmlspecialchars($evt['event_name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
              </div>

              <div class="form-row" style="display:flex; gap:16px;">
                  <div class="form-group" style="flex:1;">
                      <label for="form_n_price">Normal Price (Ks)</label>
                      <input type="number" id="form_n_price" name="n_price" class="form-control" value="0" required>
                  </div>
                  <div class="form-group" style="flex:1;">
                      <label for="form_e_price">Event Price (Ks)</label>
                      <input type="number" id="form_e_price" name="e_price" class="form-control" value="0">
                  </div>
              </div>

              <div class="form-row" style="display:flex; gap:16px;">
                  <div class="form-group" style="flex:1;">
                      <label for="form_point_reward">Reward Points</label>
                      <input type="number" id="form_point_reward" name="point_reward" class="form-control" value="0" required>
                  </div>
                  <div class="form-group" style="flex:1;">
                      <label for="form_status">Status</label>
                      <select id="form_status" name="status" class="form-control">
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>
                      </select>
                  </div>
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn" style="background:var(--bg-color); color:var(--text-main);" onclick="closeItemModal()">Cancel</button>
                  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
              </div>
          </form>
      </div>
  </div>

  <!-- Hidden Delete Form (အမှိုက်ပုံးနှိပ်လိုက်လျှင် ဖျက်ရန်အတွက် Database သို့ ပို့မည့် လျှို့ဝှက် Form) -->
  <form id="deleteForm" action="<?= APP_URL ?>/admin/items" method="POST" style="display: none;">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="item_id" id="delete_item_id" value="">
  </form>
