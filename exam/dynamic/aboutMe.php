<div class="container my-5" x-data="aboutMeComponent()" x-init="fetchAboutMe()">
  <div class="text-center mb-4">
    <h2 class="font-weight-bold">About Me</h2>
    <hr class="w-25 mx-auto">
  </div>

  <template x-if="aboutMe">
    <div class="row">
      <div class="col-md-6 mb-4" x-show="aboutMe.interests">
        <h5 class="text-muted">Interests</h5>
        <p class="lead" x-text="aboutMe.interests"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.inspiration">
        <h5 class="text-muted">Inspiration</h5>
        <p class="lead" x-text="aboutMe.inspiration"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.life_motto">
        <h5 class="text-muted">Life Motto</h5>
        <p class="lead" x-text="aboutMe.life_motto"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.bucket_list">
        <h5 class="text-muted">Bucket List</h5>
        <p class="lead" x-text="aboutMe.bucket_list"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.strengths">
        <h5 class="text-muted">Strengths</h5>
        <p class="lead" x-text="aboutMe.strengths"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.weaknesses">
        <h5 class="text-muted">Weaknesses</h5>
        <p class="lead" x-text="aboutMe.weaknesses"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.talents">
        <h5 class="text-muted">Talents</h5>
        <p class="lead" x-text="aboutMe.talents"></p>
      </div>
      <div class="col-md-6 mb-4" x-show="aboutMe.greatest_fear">
        <h5 class="text-muted">Greatest Fear</h5>
        <p class="lead" x-text="aboutMe.greatest_fear"></p>
      </div>
    </div>
  </template>

  <div class="text-center mt-4">
    <button class="btn btn-primary" @click="openModal('edit', aboutMe)">Edit About Me</button>
    <button class="btn btn-danger" @click="openDeleteModal()" x-show="aboutMe">Clear Content</button>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="aboutMeModal" tabindex="-1" role="dialog" aria-hidden="true" x-ref="modal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveAboutMe()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" x-text="modalTitle"></h5>
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
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true" x-ref="deleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
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

    openModal(mode, data = null) {
      if (mode === 'edit' && data) {
        this.form = { ...data };
        this.modalTitle = 'Edit About Me';
      } else {
        this.form = {
          id: null,
          interests: '',
          inspiration: '',
          life_motto: '',
          bucket_list: '',
          strengths: '',
          weaknesses: '',
          talents: '',
          greatest_fear: ''
        };
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
