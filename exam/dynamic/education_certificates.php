<div class="container my-4" x-data="eduCertComponent()" x-init="init()">
  <!-- Section Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="font-weight-bold mb-0" style="color:#000;">Education & Certificates</h2>
    <div>
      <button class="btn btn-primary mr-2" @click="openEduModal('add')">Add Education</button>
      <button class="btn btn-primary" @click="openCertModal('add')">Add Certificate</button>
    </div>
  </div>
  <hr class="w-25 mx-auto">

  <div class="row mt-4">
    <!-- Education Column -->
    <div class="col-md-4">
      <h4 class="font-weight-bold mb-3" style="color:#000;">Education</h4>
      <template x-for="edu in education" :key="edu.id">
        <div class="card mb-4 p-3"
             style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
          <h5 class="font-weight-bold" style="color:#000;" x-text="edu.school_name"></h5>
          <small class="text-muted">
            <span x-text="edu.year_start"></span> - <span x-text="edu.year_end"></span>
          </small>
          <p class="mt-2" style="color:#000;" x-text="edu.description"></p>
          <div class="mt-2">
            <button class="btn btn-sm btn-outline-primary mr-2" @click="openEduModal('edit', edu)">Edit</button>
            <button class="btn btn-sm btn-outline-danger" @click="openEduDeleteModal(edu)">Delete</button>
          </div>
        </div>
      </template>
    </div>

    <!-- Certificates Column -->
    <div class="col-md-8">
      <h4 class="font-weight-bold mb-3" style="color:#000;">Certificates</h4>
      <div class="row">
        <template x-for="cert in certificates" :key="cert.id">
          <div class="col-md-4 mb-4">
            <div class="card h-100"
                 style="border: 1px solid #7fb2ff; background:#ffffff; border-radius:15px;">
              <img :src="'../images/' + cert.img_src" class="card-img-top" alt="Certificate Image">
              <div class="card-body d-flex flex-column">
                <h5 class="font-weight-bold" style="color:#000;" x-text="cert.title"></h5>
                <small class="text-muted">Obtained: <span x-text="cert.year_obtained"></span></small>
                <div class="mt-3">
                  <button class="btn btn-sm btn-outline-primary mr-2" @click="openCertModal('edit', cert)">Edit</button>
                  <button class="btn btn-sm btn-outline-danger" @click="openCertDeleteModal(cert)">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>

  <!-- Education Modal -->
  <div class="modal fade" id="eduModal" tabindex="-1" role="dialog" x-ref="eduModal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveEdu()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" x-text="eduModalTitle"></h5>
          <button type="button" class="close" @click="closeEduModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" x-model="eduForm.id">
          <div class="form-group">
            <label>School Name</label>
            <input type="text" class="form-control" x-model="eduForm.school_name" required>
          </div>
          <div class="form-group">
            <label>Year Start</label>
            <input type="number" class="form-control" x-model="eduForm.year_start">
          </div>
          <div class="form-group">
            <label>Year End</label>
            <input type="number" class="form-control" x-model="eduForm.year_end">
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" x-model="eduForm.description"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeEduModal()">Cancel</button>
          <button type="submit" class="btn btn-success" :disabled="isSavingEdu">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Education Delete Confirmation Modal -->
  <div class="modal fade" id="eduDeleteModal" tabindex="-1" role="dialog" x-ref="eduDeleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="close" @click="closeEduDeleteModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete "<strong x-text="deleteEdu?.school_name"></strong>"?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeEduDeleteModal()">Cancel</button>
          <button type="button" class="btn btn-danger" @click="deleteEduConfirm()" :disabled="isDeletingEdu">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Certificate Modal -->
  <div class="modal fade" id="certModal" tabindex="-1" role="dialog" x-ref="certModal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveCert()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" x-text="certModalTitle"></h5>
          <button type="button" class="close" @click="closeCertModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" x-model="certForm.id">
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" x-model="certForm.title" required>
          </div>
          <div class="form-group">
            <label>Year Obtained</label>
            <input type="number" class="form-control" x-model="certForm.year_obtained">
          </div>
          <div class="form-group">
            <label>Image</label>
            <input type="file" class="form-control-file" @change="handleCertImage($event)">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeCertModal()">Cancel</button>
          <button type="submit" class="btn btn-success" :disabled="isSavingCert">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Certificate Delete Confirmation Modal -->
  <div class="modal fade" id="certDeleteModal" tabindex="-1" role="dialog" x-ref="certDeleteModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="close" @click="closeCertDeleteModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete "<strong x-text="deleteCert?.title"></strong>"?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeCertDeleteModal()">Cancel</button>
          <button type="button" class="btn btn-danger" @click="deleteCertConfirm()" :disabled="isDeletingCert">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function eduCertComponent() {
  return {
    // Collections
    education: [],
    certificates: [],

    // Forms
    eduForm: { id: null, school_name: '', year_start: '', year_end: '', description: '' },
    certForm: { id: null, title: '', year_obtained: '', img_src: '' },
    certFile: null,

    // Modal titles
    eduModalTitle: 'Add Education',
    certModalTitle: 'Add Certificate',

    // Delete state
    deleteEdu: null,
    deleteCert: null,

    // Operation guards
    _initDone: false,
    isSavingEdu: false,
    isSavingCert: false,
    isDeletingEdu: false,
    isDeletingCert: false,

    // Init once
    init() {
      if (this._initDone) return;
      this._initDone = true;
      this.refresh();
    },

    // Read and replace arrays
    refresh() {
      fetch('backend/crud_eduCert.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'read' })
      })
      .then(res => res.json())
      .then(data => {
        this.education = Array.isArray(data.education) ? data.education : [];
        this.certificates = Array.isArray(data.certificates) ? data.certificates : [];
      })
      .catch(() => {
        this.education = [];
        this.certificates = [];
      });
    },

    // Education
    openEduModal(mode, edu = null) {
      this.eduModalTitle = mode === 'edit' ? 'Edit Education' : 'Add Education';
      if (edu) {
        this.eduForm = {
          id: edu.id ? edu.id : null,
          school_name: edu.school_name ? edu.school_name : '',
          year_start: edu.year_start ? edu.year_start : '',
          year_end: edu.year_end ? edu.year_end : '',
          description: edu.description ? edu.description : ''
        };
      } else {
        this.eduForm = { id: null, school_name: '', year_start: '', year_end: '', description: '' };
      }
      $(this.$refs.eduModal).modal('show');
    },
    closeEduModal() {
      $(this.$refs.eduModal).modal('hide');
    },
    saveEdu() {
      if (this.isSavingEdu) return;
      this.isSavingEdu = true;

      const action = this.eduForm.id ? 'updateEdu' : 'createEdu';
      const payload = {
        action: action,
        id: this.eduForm.id || null,
        school_name: this.eduForm.school_name,
        year_start: this.eduForm.year_start,
        year_end: this.eduForm.year_end,
        description: this.eduForm.description
      };

      fetch('backend/crud_eduCert.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.closeEduModal();
          this.refresh();
          Swal.fire({ icon: 'success', title: 'Saved!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to save.' });
        }
      })
      .finally(() => {
        this.isSavingEdu = false;
      });
    },

    openEduDeleteModal(edu) {
      this.deleteEdu = edu
        ? {
            id: edu.id ? edu.id : null,
            school_name: edu.school_name ? edu.school_name : '',
            year_start: edu.year_start ? edu.year_start : '',
            year_end: edu.year_end ? edu.year_end : '',
            description: edu.description ? edu.description : ''
          }
        : null;
      $(this.$refs.eduDeleteModal).modal('show');
    },
    closeEduDeleteModal() {
      $(this.$refs.eduDeleteModal).modal('hide');
      this.deleteEdu = null;
    },
    deleteEduConfirm() {
      if (this.isDeletingEdu || !this.deleteEdu) return;
      this.isDeletingEdu = true;

      fetch('backend/crud_eduCert.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'deleteEdu', id: Number(this.deleteEdu.id) })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.refresh();
          this.closeEduDeleteModal();
          Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to delete.' });
        }
      })
      .finally(() => {
        this.isDeletingEdu = false;
      });
    },

    // Certificates
    openCertModal(mode, cert = null) {
      this.certModalTitle = mode === 'edit' ? 'Edit Certificate' : 'Add Certificate';
      if (cert) {
        this.certForm = {
          id: cert.id ? cert.id : null,
          title: cert.title ? cert.title : '',
          year_obtained: cert.year_obtained ? cert.year_obtained : '',
          img_src: cert.img_src ? cert.img_src : ''
        };
      } else {
        this.certForm = { id: null, title: '', year_obtained: '', img_src: '' };
      }
      this.certFile = null;
      $(this.$refs.certModal).modal('show');
    },
    closeCertModal() {
      $(this.$refs.certModal).modal('hide');
      this.certFile = null;
    },
    handleCertImage(e) {
      this.certFile = e.target.files && e.target.files[0] ? e.target.files[0] : null;
    },
    saveCert() {
      if (this.isSavingCert) return;
      this.isSavingCert = true;

      const action = this.certForm.id ? 'updateCert' : 'createCert';
      let request;

      if (this.certFile) {
        const formData = new FormData();
        formData.append('action', action);
        if (this.certForm.id) formData.append('id', this.certForm.id);
        formData.append('title', this.certForm.title);
        formData.append('year_obtained', this.certForm.year_obtained || '');
        formData.append('image', this.certFile);
        request = { method: 'POST', body: formData };
      } else {
        const payload = {
          action: action,
          id: this.certForm.id || null,
          title: this.certForm.title,
          year_obtained: this.certForm.year_obtained || '',
          img_src: this.certForm.img_src || null
        };
        request = {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        };
      }

      fetch('backend/crud_eduCert.php', request)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.closeCertModal();
          this.refresh();
          Swal.fire({ icon: 'success', title: 'Saved!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to save.' });
        }
      })
      .finally(() => {
        this.isSavingCert = false;
      });
    },

    openCertDeleteModal(cert) {
      this.deleteCert = cert
        ? {
            id: cert.id ? cert.id : null,
            title: cert.title ? cert.title : '',
            year_obtained: cert.year_obtained ? cert.year_obtained : '',
            img_src: cert.img_src ? cert.img_src : ''
          }
        : null;
      $(this.$refs.certDeleteModal).modal('show');
    },
    closeCertDeleteModal() {
      $(this.$refs.certDeleteModal).modal('hide');
      this.deleteCert = null;
    },
    deleteCertConfirm() {
      if (this.isDeletingCert || !this.deleteCert) return;
      this.isDeletingCert = true;

      fetch('backend/crud_eduCert.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'deleteCert', id: Number(this.deleteCert.id) })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.refresh();
          this.closeCertDeleteModal();
          Swal.fire({ icon: 'success', title: 'Deleted!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to delete.' });
        }
      })
      .finally(() => {
        this.isDeletingCert = false;
      });
    }
  }
}
</script>