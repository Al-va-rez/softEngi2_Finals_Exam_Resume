<div class="container my-5" x-data="aboutMeComponent()" x-init="fetchAboutMe()">
  <!-- Header with buttons -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="font-weight-bold mb-0">About Me</h2>
    <div>
      <button class="btn btn-primary mr-2" @click="openModal('edit', aboutMe)">Edit</button>
      <button class="btn btn-danger" @click="openDeleteModal()" x-show="aboutMe">Clear Content</button>
    </div>
  </div>
  <hr class="w-25 mx-auto">

  <template x-if="aboutMe">
    <div>
      <!-- Combined Paragraph -->
      <div class="card p-4 mt-3"
           style="border: 1px solid #bcd4ff; box-shadow:0 4px 10px rgba(0,0,0,0.05); background:#ffffff;">
        <p style="font-size: 1rem; color:#000;">
          <span x-text="aboutMe.interests"></span>.
          <span x-text="aboutMe.inspiration"></span>.
          <span x-text="aboutMe.bucket_list"></span>.
        </p>
      </div>

      <!-- Individual Cards -->
      <div class="row mt-4">
        <div class="col-md-6 mb-4" x-show="aboutMe.strengths">
          <div class="card h-100 p-3"
               style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
            <h5 class="font-weight-bold" style="color:#000;">Strengths</h5>
            <p style="color:#000;" x-text="aboutMe.strengths"></p>
          </div>
        </div>

        <div class="col-md-6 mb-4" x-show="aboutMe.weaknesses">
          <div class="card h-100 p-3"
               style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
            <h5 class="font-weight-bold" style="color:#000;">Weaknesses</h5>
            <p style="color:#000;" x-text="aboutMe.weaknesses"></p>
          </div>
        </div>

        <div class="col-md-6 mb-4" x-show="aboutMe.talents">
          <div class="card h-100 p-3"
               style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
            <h5 class="font-weight-bold" style="color:#000;">Talents</h5>
            <p style="color:#000;" x-text="aboutMe.talents"></p>
          </div>
        </div>

        <div class="col-md-6 mb-4" x-show="aboutMe.greatest_fear">
          <div class="card h-100 p-3"
               style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
            <h5 class="font-weight-bold" style="color:#000;">Greatest Fear</h5>
            <p style="color:#000;" x-text="aboutMe.greatest_fear"></p>
          </div>
        </div>
      </div>

      <!-- Life Motto at bottom -->
      <div class="mt-4 text-center" x-show="aboutMe.life_motto">
        <p class="font-italic" style="font-size: 1.1rem; color:#000;">
          "<span x-text="aboutMe.life_motto"></span>"
        </p>
      </div>
    </div>
  </template>

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
          this.form = {
            id: data.id ? data.id : null,
            interests: data.interests ? data.interests : '',
            inspiration: data.inspiration ? data.inspiration : '',
            life_motto: data.life_motto ? data.life_motto : '',
            bucket_list: data.bucket_list ? data.bucket_list : '',
            strengths: data.strengths ? data.strengths : '',
            weaknesses: data.weaknesses ? data.weaknesses : '',
            talents: data.talents ? data.talents : '',
            greatest_fear: data.greatest_fear ? data.greatest_fear : ''
          };
        }
      });
    },

    openModal(mode, data = null) {
      if (mode === 'edit' && data) {
        this.form = {
          id: data.id ? data.id : null,
          interests: data.interests ? data.interests : '',
          inspiration: data.inspiration ? data.inspiration : '',
          life_motto: data.life_motto ? data.life_motto : '',
          bucket_list: data.bucket_list ? data.bucket_list : '',
          strengths: data.strengths ? data.strengths : '',
          weaknesses: data.weaknesses ? data.weaknesses : '',
          talents: data.talents ? data.talents : '',
          greatest_fear: data.greatest_fear ? data.greatest_fear : ''
        };
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
      const payload = {
        action: action,
        id: this.form.id,
        interests: this.form.interests,
        inspiration: this.form.inspiration,
        life_motto: this.form.life_motto,
        bucket_list: this.form.bucket_list,
        strengths: this.form.strengths,
        weaknesses: this.form.weaknesses,
        talents: this.form.talents,
        greatest_fear: this.form.greatest_fear
      };

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
