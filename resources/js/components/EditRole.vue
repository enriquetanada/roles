<template>
    <div>
         <b-modal id="edit-role" ref="edit-role" title="Edit Role" hide-footer centered @hidden="onResetModal" >
            <b-form @submit.prevent="submitProjectForm">
                <b-form-group
                    class="mb-3"
                    label="Role"
                    label-for="name"
                    :state="state_role"
                >
                    <b-form-input id="role" v-model="role" :state="state_role" trim></b-form-input>
                    <b-form-invalid-feedback>{{ err_role }}</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    class="mb-3"
                    label="Role"
                    label-for="add_permission"
                    :state="state_role"
                >
                    <multiselect
                            id="role"
                            class="yr-form-multiselect"
                            :class="
                                state_role === false
                                ? 'multiselect-error'
                                : '' "
                            :options="permissions"
                            v-model="permission"
                            :multiple="true"
                            track-by="id"
                            label="name"
                            placeholder="Select role"
                            :allow-empty="false">
                    </multiselect>
                    <b-form-invalid-feedback :state="state_role">{{ err_role }}</b-form-invalid-feedback>
                </b-form-group>
                <hr>
                <div class="d-grid pt-1 mb-3">
                    <b-form-group>
                        <b-button
                            v-if="!isSaving"
                            class="btn btn-dark btn-lg btn-block"
                            type="submit">
                        Edit Role
                        </b-button>
                        <b-button
                            v-else
                            class="btn btn-dark btn-lg btn-block"
                            disabled>
                            <b-spinner small type="grow"></b-spinner>
                            Editing
                        </b-button>
                    </b-form-group>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>

export default {
    props: ['roleData', 'permissions'],
    data() {
        return {
            role: "",
            permission: "",

            state_role: null,
            state_permission: null,
            
            err_role: "",
            err_permission: "",

            id: "",
            isSaving: false
        }
    },
    methods: {
        onClearFields() {
            this.role = this.roleData.name ? this.roleData.name : "";
            this.permission = this.roleData.permissions ? this.roleData.permissions : "";
        },
        onClearErrors() {
            this.err_permission = '';
            this.state_permission = null;
            this.err_role = '';
            this.state_role = null;
        },
        submitProjectForm() {
            this.isSaving = true;
            this.onClearErrors();
            const permissions = Object.values(this.permission).map(item => {
                return item.name;
            })
            this.$query('saveEditRole', {
                role: {
                    id: this.id,
                    role: this.role,
                    permission: JSON.stringify(permissions)
                },
            }).then((res) => {
                this.isSaving = false;
                if (res.data.errors) {
                    let errors = Object.values(
                        res.data.errors[0].extensions.validation,
                    ).flat();
                    let errors_keys = Object.keys(
                        res.data.errors[0].extensions.validation,
                    ).flat();

                    const error_message = (name, index, state) => {
                        this[name] = errors_keys.some((q) => q == index)
                            ? errors[errors_keys.indexOf(index)]
                            : '';

                        if (this[name]) {
                            this[state] = false;
                        }
                    };
                    error_message('err_role', 'role.role', 'state_role');
                    error_message('err_permission', 'role.permission', 'state_permission');
                } else {
                    let response = res.data.data.saveEditRole;
                    if (response.error) {
                        this.$swal({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    } else {
                        this.$emit('success');
                        this.$refs['edit-role'].hide();
                        this.onClearFields();
                        this.onClearErrors();
                        this.$swal({
                            title: 'Success',
                            text: response.message,
                            icon: 'success'
                        });
                    }
                }
            });
        },
        onResetModal() {
            this.onClearFields();
            this.onClearErrors();
        },
    },
    watch: {
        roleData(data) {
            this.role = data.name ? data.name : '';
            this.permission = data.permissions ? data.permissions : "";
            this.id = data.id ? data.id : "";
        }
    },
}
</script>
