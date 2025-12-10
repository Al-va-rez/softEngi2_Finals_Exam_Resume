<div class="container my-4" x-data="projectsComponent()" x-init="fetchProjects()">
  <div class="text-center mb-4">
    <h2 class="font-weight-bold">Latest Works</h2>
    <hr class="w-25 mx-auto">
  </div>

  <div class="row">
    <template x-for="project in projects" :key="project.id">
      <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <img :src="'../images/' + project.img_src" class="card-img-top" alt="Project image" x-show="project.img_src">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title font-weight-bold mb-1" x-text="project.title"></h5>
            <p class="text-muted mb-2" x-text="'Category: ' + project.category"></p>
            <p class="card-text mb-3" x-text="project.description"></p>
            <a :href="project.github_link" target="_blank" class="btn btn-outline-dark btn-sm mb-3" x-show="project.github_link">View on GitHub</a>
            <div class="mt-auto">
              <button class="btn btn-sm btn-info mr-2" @click="openModal('edit', project)">Edit</button>
              <button class="btn btn-sm btn-danger" @click="openDeleteModal(project)">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </template>
    <div class="col-12 text-center" x-show="projects.length === 0">
      <p class="text-muted">No projects added yet.</p>
    </div>
  </div>

  <div class="text-center mt-4">
    <button class="btn btn-success" @click="openModal('add')">Add Project</button>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal fade" id="projectModal" tabindex="-1" role="dialog" x-ref="projectModal">
    <div class="modal-dialog" role="document">
      <form @submit.prevent="saveProject()" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" x-text="modalTitle"></h5>
          <button type="button" class="close" @click="closeModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" x-model="form.id">
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" x-model="form.title" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <input type="text" class="form-control" x-model="form.category" required>
          </div>
          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" x-model="form.description"></textarea>
          </div>
          <div class="form-group">
            <label>GitHub Link</label>
            <input type="url" class="form-control" x-model="form.github_link">
          </div>
          <div class="form-group">
            <label>Image</label>
            <input type="file" class="form-control-file" @change="handleFileUpload($event)">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal()">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" x-ref="deleteProjectModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Deletion</h5>
          <button type="button" class="close" @click="closeDeleteModal()"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete "<strong x-text="deleteProject.title"></strong>"?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeDeleteModal()">Cancel</button>
          <button type="button" class="btn btn-danger" @click="deleteProjectConfirm()">Delete</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function projectsComponent() {
  return {
    projects: [],
    form: { id: null, title: '', category: '', description: '', github_link: '', img_src: '' },
    deleteProject: { id: null, title: '' },
    modalTitle: 'Add Project',
    file: null,

    fetchProjects() {
      fetch('backend/crud_projects.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'read' })
      })
      .then(res => res.json())
      .then(data => { this.projects = Array.isArray(data) ? data : []; });
    },

    handleFileUpload(e) {
      this.file = e.target.files[0];
    },

    openModal(mode, project = null) {
      if (mode === 'edit' && project) {
        this.form = { ...project };
        this.modalTitle = 'Edit Project';
      } else {
        this.form = { id: null, title: '', category: '', description: '', github_link: '', img_src: '' };
        this.modalTitle = 'Add Project';
      }
      this.file = null;
      $(this.$refs.projectModal).modal('show');
    },

    closeModal() {
      $(this.$refs.projectModal).modal('hide');
    },

    saveProject() {
      const action = this.form.id ? 'update' : 'create';
      let requestOptions;

      if (this.file) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('id', this.form.id || '');
        formData.append('title', this.form.title);
        formData.append('category', this.form.category);
        formData.append('description', this.form.description);
        formData.append('github_link', this.form.github_link);
        formData.append('image', this.file);
        requestOptions = { method: 'POST', body: formData };
      } else {
        const payload = { action, ...this.form };
        requestOptions = {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        };
      }

      fetch('backend/crud_projects.php', requestOptions)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.closeModal();
          this.fetchProjects();
          Swal.fire({ icon: 'success', title: 'Saved!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Oops...', text: data.message });
        }
      });
    },

    openDeleteModal(project) {
      this.deleteProject = { ...project };
      $(this.$refs.deleteProjectModal).modal('show');
    },

    closeDeleteModal() {
      $(this.$refs.deleteProjectModal).modal('hide');
    },

    deleteProjectConfirm() {
      fetch('backend/crud_projects.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: this.deleteProject.id })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          this.projects = this.projects.filter(p => p.id !== this.deleteProject.id);
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