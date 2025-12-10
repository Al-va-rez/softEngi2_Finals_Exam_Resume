<div class="container my-4" x-data="aboutMeComponent()" x-init="fetchAboutMe()">

  <h2 class="mb-4">About Me</h2>

  <!-- Display Section -->
  <template x-if="aboutMe">
    <div class="card mb-3">
      <div class="card-body">
        <p><strong>Interests:</strong> <span x-text="aboutMe.interests"></span></p>
        <p><strong>Inspiration:</strong> <span x-text="aboutMe.inspiration"></span></p>
        <p><strong>Life Motto:</strong> <span x-text="aboutMe.life_motto"></span></p>
        <p><strong>Bucket List:</strong> <span x-text="aboutMe.bucket_list"></span></p>
        <p><strong>Strengths:</strong> <span x-text="aboutMe.strengths"></span></p>
        <p><strong>Weaknesses:</strong> <span x-text="aboutMe.weaknesses"></span></p>
        <p><strong>Talents:</strong> <span x-text="aboutMe.talents"></span></p>
        <p><strong>Greatest Fear:</strong> <span x-text="aboutMe.greatest_fear"></span></p>
      </div>
      <div class="card-footer text-right">
        <button class="btn btn-sm btn-primary" @click="openModal('edit')">Edit</button>
        <button class="btn btn-sm btn-danger" @click="openDeleteModal()">Delete</button>
      </div>
    </div>
  </template>

  <!-- Empty State -->
  <div class="alert alert-info" x-show="!aboutMe">
    No About Me record found. 
    <button class="btn btn-sm btn-success ml-2" @click="openModal('add')">Add</button>
  </div>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="aboutMeModal" tabindex="-1" role="dialog" aria-labelledby="aboutMeModalLabel" aria-hidden="true" x-ref="modal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveAboutMe()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="aboutMeModalLabel" x-text="modalTitle"></h5>
          <button type="button" class="close" @click="closeModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Interests</label>
            <input type="text" class="form-control" x-model="form.interests">
          </div>
          <div class="form-group">
            <label>Inspiration</label>
            <input type="text" class="form-control" x-model="form.inspiration">
          </div>
          <div class="form-group">
            <label>Life Motto</label>
            <input type="text" class="form-control" x-model="form.life_motto">
          </div>
          <div class="form-group">
            <label>Bucket List</label>
            <textarea class="form-control" x-model="form.bucket_list"></textarea>
          </div>
          <div class="form-group">
            <label>Strengths</label>
            <input type="text" class="form-control" x-model="form.strengths">
          </div>
          <div class="form-group">
            <label>Weaknesses</label>
            <input type="text" class="form-control" x-model="form.weaknesses">
          </div>
          <div class="form-group">
            <label>Talents</label>
            <input type="text" class="form-control" x-model="form.talents">
          </div>
          <div class="form-group">
            <label>Greatest Fear</label>
            <input type="text" class="form-control" x-model="form.greatest_fear">
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
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" x-ref="deleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
          <button type="button" class="close" @click="closeDeleteModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete your About Me record?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeDeleteModal()">Cancel</button>
          <button type="button" class="btn btn-danger" @click="deleteAboutMeConfirm()">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function aboutMeComponent() {
  return {
    aboutMe: null,
    form: {
      id: null,
      interests: '',
      inspiration: '',
      life_motto: '',
      bucket_list: '',
      strengths: '',
      weaknesses: '',
      talents: '',
      greatest_fear: ''
    },
    modalTitle: 'Add About Me',

    fetchAboutMe() {
      fetch('backend/crud_aboutMe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'read' })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status && data.status === 'error') {
          this.aboutMe = null;
        } else {
          this.aboutMe = data;
          this.form = { ...data };
        }
      });
    },

    openModal(mode) {
      if (mode === 'edit' && this.aboutMe) {
        this.form = { ...this.aboutMe };
        this.modalTitle = 'Edit About Me';
      } else {
        this.form = { id: null, interests: '', inspiration: '', life_motto: '', bucket_list: '', strengths: '', weaknesses: '', talents: '', greatest_fear: '' };
        this.modalTitle = 'Add About Me';
      }
      $(this.$refs.modal).modal('show');
    },

    closeModal() {
      $(this.$refs.modal).modal('hide');
    },

    saveAboutMe() {
      const action = this.form.id ? 'update' : 'create';
      const payload = { action, ...this.form };

      fetch('backend/crud_aboutMe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.closeModal();
          this.fetchAboutMe();
          Swal.fire({ icon: 'success', title: 'Saved!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
        }
      });
    },

    openDeleteModal() {
      $(this.$refs.deleteModal).modal('show');
    },

    closeDeleteModal() {
      $(this.$refs.deleteModal).modal('hide');
    },

    deleteAboutMeConfirm() {
      fetch('backend/crud_aboutMe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: this.form.id })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.aboutMe = null;
          this.form = { id: null, interests: '', inspiration: '', life_motto: '', bucket_list: '', strengths: '', weaknesses: '', talents: '', greatest_fear: '' };
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
