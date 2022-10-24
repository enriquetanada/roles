<template>
    <div>
         <b-modal id="add-user" ref="add-user" title="Add New User" hide-footer centered @hidden="onResetModal" >
            <b-form @submit.prevent="submitProjectForm">
                <b-form-group
                    class="mb-3"
                    label="Name"
                    label-for="name"
                    :state="state_name"
                >
                    <b-form-input id="name" v-model="name" :state="state_name" trim></b-form-input>
                    <b-form-invalid-feedback>{{ err_name }}</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    class="mb-3"
                    label="Email"
                    label-for="add_email"
                    :state="state_email"
                >
                    <b-form-input id="add_email" v-model="email" :state="state_email" trim></b-form-input>
                    <b-form-invalid-feedback>{{ err_email }}</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    class="mb-3"
                    label="Password"
                    type="password"
                    label-for="add_password"
                    :state="state_password"
                >
                    <b-form-input id="add_password" v-model="password" :state="state_password" trim></b-form-input>
                    <b-form-invalid-feedback>{{ err_password }}</b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    class="mb-3"
                    label="Role"
                    label-for="add_role"
                    :state="state_role"
                >
                    <multiselect
                            class="yr-form-multiselect"
                            :class="
                                state_role === false
                                ? 'multiselect-error'
                                : '' "
                            :options="roles"
                            v-model="role"
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
                        Add user
                        </b-button>
                        <b-button
                            v-else
                            class="btn btn-dark btn-lg btn-block"
                            disabled>
                            <b-spinner small type="grow"></b-spinner>
                            Adding
                        </b-button>
                    </b-form-group>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>

export default {
    props: ['roles'],
    data() {
        return {
            name: "",
            email: "",
            role: "",
            password: "",

            state_name: null,
            state_email: null,
            state_role: null,
            state_password: null,
            
            err_name: "",
            err_email: "",
            err_role: "",
            err_password: "",

            isSaving: false
        }
    },
    methods: {
        onClearFields() {
            this.name = '';
            this.email = '';
            this.role = '';
            this.password = '';
        },
        onClearErrors() {
            this.err_name = '';
            this.state_name = null;
            this.err_email = '';
            this.state_email = null;
            this.err_role = '';
            this.state_role = null;
            this.err_password = '';
            this.state_password = null;
        },
        submitProjectForm() {
            this.isSaving = true;
            this.onClearErrors();
            this.$query('saveAddUser', {
                user: {
                    name: this.name,
                    email: this.email,
                    role: this.role.name,
                    password: this.password
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
                    error_message('err_name', 'user.name', 'state_name');
                    error_message('err_email', 'user.email', 'state_email');
                    error_message('err_role', 'user.role', 'state_role');
                    error_message('err_password', 'user.password', 'state_password');
                } else {
                    let response = res.data.data.saveAddUser;
                    if (response.error) {
                        this.$swal({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    } else {
                        this.$emit('success');
                        this.$refs['add-user'].hide();
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
    }
}
</script>
