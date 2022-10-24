<template>
  <div>
    <Navigation />
    <section class="container my-5">
            <!-- Button for add project modal -->
            <div class="row justify-content-between">
                <div class="col-auto">
                    <h2 class="mb-2">Permissions</h2>
                </div>
                <div class="col-auto">
                    <b-button 
                        v-b-modal.modal-sm="'add-permission'" 
                        class="btn" 
                        variant="primary" 
                        size="sm" 
                        v-if="withPermission('permission_create')">
                    Add new permission
                    </b-button>
                </div>
            </div>

            <hr>

            <b-form-group
                label="Search project"
                label-for="filter-input"
                label-align-sm="left"
                label-size="sm"
                class="mb-3"
            >
                  <b-input-group size="sm">
                        <b-form-input
                        id="filter-input"
                        v-model="table_options.filter"
                        type="search"
                        placeholder="Type to Search"
                        ></b-form-input>
                  </b-input-group>
            </b-form-group>

            <b-table
                id="users"
                responsive
                :items="permissions"
                class="yr-table"
                :filter="table_options.filter"
                :fields="table_options.fields"
                :sort-by.sync="table_options.sortBy"
                :sort-desc.sync="table_options.sortDesc"
                :busy="table_options.isBusy"
                :per-page="table_options.perPage"
                :current-page="table_options.currentPage"
                striped
                show-empty
                @filtered="onFiltered">

                <template #cell(actions)="row">
                    <b-button 
                        class="mr-1 my-1" 
                        variant="dark" 
                        size="sm" 
                        v-b-modal.modal-sm="'edit-permission'" 
                        @click.prevent="onEditUser(row.item)"
                        v-if="withPermission('permission_edit')">
                    Edit
                    </b-button>
                    <b-button 
                        class="mr-1 my-1" 
                        variant="danger" 
                        size="sm"  
                        @click="onDeleteUser(row.item.id)"
                        v-if="withPermission('permission_delete')">
                    Delete
                    </b-button>
                </template>
            </b-table>
            <b-col>
                <h6 class="ml-5 yr-table-entries">
                    {{
                        showEntries(
                            table_options.perPage,
                            table_options.currentPage,
                            table_options.perPage,
                            table_options.rows,
                        )
                    }}
                </h6>
            </b-col>
            <b-col class="my-1">
                <b-pagination
                    v-model="table_options.currentPage"
                    :total-rows="table_options.rows"
                    :per-page="table_options.perPage"
                    align="right"
                    size="sm"
                    class="yr-table-paginate"
                    aria-controls="area"
                >
                </b-pagination>
            </b-col>
        </section>
        <AddPermissions  @success="OnSuccess"/>
        <EditPermissions :permission="permission" @success="OnSuccess"/>

  </div>
</template>

<script>
import Navigation from './Navigation';
import AddPermissions from './AddPermission';
import EditPermissions from './EditPermission';

  export default {
    components: {
        Navigation,
        AddPermissions,
        EditPermissions
    },
    data() {
      return {
          table_options: {
              isBusy: true,
              sortBy: 'title',
              sortDesc: false,
              selected: '',
              show: false,
              pageOptions: [5, 10, 20, 50],
              fields: [
                  { key: 'name', sortable: true },
                  { key: 'actions' },
              ],
              filter: null,
              rows: 1,
              currentPage: 1,
              perPage: 10,
          },
          permissions: [],
          permission: [],
          
      };
    },
    computed: {
        rows() {
            return this.users.length;
        },
    },
    created() {
        this.onCreated();
    },
    methods: {
        OnSuccess() {
            this.onCreated();
        }, 
        onCreated() {
            this.$query('getPermissions').then((res) => {
                let response = res.data.data.getPermissions;
                this.table_options.isBusy = false;
                if (!response.error) {
                    this.permissions = response;
                    this.table_options.rows = this.permissions.length;
                } else {
                    this.$router.push({ name: 'dashboard' });
                }
            });
        },
        onSuccess() {
            this.onCreated();
        },
        onEditUser(data) {
            this.permission = data;
        },
        onDeleteUser(id) {
            this.$swal({
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((res) => {
                if (res.isConfirmed) {
                    this.$query('deletePermission', {
                        deletePermission: parseInt(id),
                    }).then((res) => {
                        let response = res.data.data.deletePermission;
                        if (response.error) {
                            this.$swal({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        } else {
                            this.$swal({
                                title: 'Success',
                                text: response.message,
                                icon: 'success'
                            });
                        }
                        this.onCreated();
                    });
                }
            });
        },
        onFiltered(filteredItems) {
            this.table_options.rows = filteredItems.length;
            this.showEntries(
                this.table_options.perPage,
                this.table_options.currentPage,
                this.table_options.perPage,
                filteredItems.length,
            );
        },
    },
  }
</script>