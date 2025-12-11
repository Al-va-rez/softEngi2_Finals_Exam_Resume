<div class="container my-5" x-data="techSkillsComponent()" x-init="fetchSkills()">
  <!-- Header with buttons -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="font-weight-bold mb-0" style="color:#000;">Technical Skills</h2>
    <div>
      <button class="btn btn-primary mr-2" @click="openModal('add')">Add Skill</button>
    </div>
  </div>
  <hr class="w-25 mx-auto">

  <!-- Skills Grid -->
  <div class="row mt-4">
    <template x-for="skill in skills" :key="skill.id">
      <div class="col-md-3 mb-4">
        <div class="card h-100 p-3"
              style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
          <h5 class="font-weight-bold" style="color:#000;" x-text="skill.name"></h5>
          <p style="color:#000;">Level: <span x-text="skill.level"></span></p>
          <div class="mt-2">
            <button class="btn btn-sm btn-outline-primary mr-2" @click="openModal('edit', skill)">Edit</button>
            <button class="btn btn-sm btn-outline-danger" @click="openDeleteModal(skill)">Delete</button>
          </div>
        </div>
      </div>
    </template>
  </div>


  <!-- Add/Edit Modal -->
  <div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-hidden="true" x-ref="skillModal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveSkill()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" x-text="modalTitle"></h5>
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
  <div class="modal fade" id="deleteSkillModal" tabindex="-1" role="dialog" aria-hidden="true" x-ref="deleteSkillModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="close" @click="closeDeleteModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete "<strong x-text="deleteSkill.name"></strong>"?
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
        this.form = {
          id: skill.id ? skill.id : null,
          name: skill.name ? skill.name : '',
          level: skill.level ? skill.level : ''
        };
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
      const payload = {
        action: action,
        id: this.form.id,
        name: this.form.name,
        level: this.form.level
      };

      fetch('backend/crud_techSkills.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
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
      this.deleteSkill = {
        id: skill.id ? skill.id : null,
        name: skill.name ? skill.name : ''
      };
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