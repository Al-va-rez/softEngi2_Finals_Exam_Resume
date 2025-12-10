<div class="container my-4" 
     x-data="techSkillsComponent()" 
     x-init="fetchSkills()">

  <h2 class="mb-4">Technical Skills</h2>

  <!-- Skills Table -->
  <table class="table table-bordered">
    <thead class="thead-light">
      <tr>
        <th>Skill</th>
        <th>Level</th>
        <th style="width:120px;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <template x-for="skill in skills" :key="skill.id">
        <tr>
          <td x-text="skill.name"></td>
          <td x-text="skill.level"></td>
          <td>
            <button class="btn btn-info btn-sm mr-2" @click="openModal('edit', skill)">Edit</button>
            <button class="btn btn-danger btn-sm" @click="openDeleteModal(skill)">Delete</button>
          </td>
        </tr>
      </template>
      <tr x-show="skills.length === 0">
        <td colspan="3" class="text-center">No skills found.</td>
      </tr>
    </tbody>
  </table>

  <!-- Add Skill Button -->
  <button class="btn btn-success mb-3" @click="openModal('add')">Add Skill</button>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-labelledby="skillModalLabel" aria-hidden="true" x-ref="skillModal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveSkill()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="skillModalLabel" x-text="modalTitle"></h5>
          <button type="button" class="close" @click="closeModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Skill Name</label>
            <input type="text" class="form-control" x-model="form.name" required>
          </div>
          <div class="form-group">
            <label>Level</label>
            <select class="form-control" x-model="form.level" required>
              <option value="">Select level</option>
              <option>Beginner</option>
              <option>Intermediate</option>
              <option>Advanced</option>
              <option>Expert</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteSkillModal" tabindex="-1" role="dialog" aria-labelledby="deleteSkillModalLabel" aria-hidden="true" x-ref="deleteSkillModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteSkillModalLabel">Confirm Deletion</h5>
          <button type="button" class="close" @click="closeDeleteModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete skill "<strong x-text="deleteSkill.name"></strong>"?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeDeleteModal()">Cancel</button>
          <button type="button" class="btn btn-danger" @click="deleteSkillConfirm()">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function techSkillsComponent() {
  return {
    skills: [],
    form: { id: null, name: '', level: '' },
    deleteSkill: { id: null, name: '' },
    modalTitle: 'Add Skill',

    fetchSkills() {
      fetch('backend/crud_techSkills.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'read' })
      })
      .then(res => res.json())
      .then(data => {
        this.skills = Array.isArray(data) ? data : [];
      });
    },

    openModal(mode, skill = null) {
      if (mode === 'edit' && skill) {
        this.form = { ...skill };
        this.modalTitle = 'Edit Skill';
      } else {
        this.form = { id: null, name: '', level: '' };
        this.modalTitle = 'Add Skill';
      }
      $(this.$refs.skillModal).modal('show');
    },

    closeModal() {
      $(this.$refs.skillModal).modal('hide');
    },

    saveSkill() {
      const action = this.form.id ? 'update' : 'create';
      fetch('backend/crud_techSkills.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, ...this.form })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.closeModal();
          this.fetchSkills();
          Swal.fire({ icon: 'success', title: 'Saved!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
        }
      });
    },

    openDeleteModal(skill) {
      this.deleteSkill = { ...skill };
      $(this.$refs.deleteSkillModal).modal('show');
    },

    closeDeleteModal() {
      $(this.$refs.deleteSkillModal).modal('hide');
    },

    deleteSkillConfirm() {
      fetch('backend/crud_techSkills.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: this.deleteSkill.id })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.skills = this.skills.filter(s => s.id !== this.deleteSkill.id);
          this.closeDeleteModal();
          Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
        }
      });
    }
  }
}
</script>