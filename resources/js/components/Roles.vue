<template>
  <div>
    <Navigation />
    <section class="container my-5">
            <!-- Button for add project modal -->
            <div class="row justify-content-between">
                <div class="col-auto">
                    <h2 class="mb-2">Roles</h2>
                </div>
                <div class="col-auto">
                    <b-button 
                        v-b-modal.modal-sm="'add-role'" 
                        class="btn" 
                        variant="primary" 
                        size="sm" 
                        v-if="withPermission('role_create')">
                    Add roles
                    </b-button>
                </div>
            </div>

            <hr>

            <!-- Table Search -->
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

            <!-- Project Table -->
            <b-table
                id="roles"
                responsive
                :items="roles"
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
                <template #cell(permissions)="row">
                    <Badge :items= onFilterPermissions(row.item.permissions) />
                </template>
                <template #cell(actions)="row">
                    <b-button 
                        class="mr-1 my-1" 
                        variant="dark" 
                        size="sm" 
                        v-b-modal.modal-sm="'edit-role'" 
                        @click.prevent="onEditRoles(row.item)"
                        v-if="withPermission('role_edit')">
                        Edit
                    </b-button>
                    <b-button 
                        class="mr-1 my-1" 
                        variant="danger" 
                        size="sm"  
                        @click="onDeleteRoles(row.item.id)"
                        v-if="withPermission('role_delete')">
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
        <AddRoles :permissions="permissions" @success="OnSuccess"/>
        <EditRoles :roleData="roleData" :permissions="permissions" @success="OnSuccess"/>

  </div>
</template>

<script>
import Navigation from './Navigation';
import AddRoles from './AddRole';
import EditRoles from './EditRole';
import Badge from './PermissionBadge'

  export default {
    components: {
        Navigation,
        AddRoles,
        EditRoles,
        Badge
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
                  { key: 'permissions', sortable: true },
                  { key: 'actions' },
              ],
              filter: null,
              rows: 1,
              currentPage: 1,
              perPage: 10,
          },
          roles: [],
          permissions: [],
          roleData: [],
          
      };
    },
    computed: {
        rows() {
            return this.roles.length;
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
            this.$query('getRolesData').then((res) => {
                this.table_options.isBusy = false;
                let response = res.data.data.getRolesData;
                if (!response.error) {
                    this.roles = response.roles;
                    this.permissions = response.permissions;
                    this.table_options.rows = this.roles.length;
                } else {
                    this.$router.push({ name: 'dashboard' });
                }
            });
        },
        onSuccess() {
            this.onCreated();
        },
        onEditRoles(data) {
            this.roleData = data;
        },
        onDeleteRoles(id) {
            this.$swal({
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((res) => {
                if (res.isConfirmed) {
                    this.$query('deleteRole', {
                        deleteRole: parseInt(id),
                    }).then((res) => {
                        let response = res.data.data.deleteRole;
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
        onFilterPermissions(items) {
            return Object.values(items).map(item => {
                return item.name;
            })
        }
    },
  }
</script>