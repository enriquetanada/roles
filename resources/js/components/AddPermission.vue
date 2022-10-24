<template>
    <div>
         <b-modal id="add-permission" ref="add-permission" title="Add Permission" hide-footer centered @hidden="onResetModal" >
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

                <hr>
                <div class="d-grid pt-1 mb-3">
                    <b-form-group>
                        <b-button
                            v-if="!isSaving"
                            class="btn btn-dark btn-lg btn-block"
                            type="submit">
                        Add Permission
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
    data() {
        return {
            name: "",
            state_name: null,
            err_name: "",
            isSaving: false
        }
    },
    methods: {
        onClearFields() {
            this.name = '';
        },
        onClearErrors() {
            this.err_name = '';
            this.state_name = null;
        },
        submitProjectForm() {
            this.isSaving = true;
            this.onClearErrors();
            this.$query('saveAddPermission', {
                permission: {
                    name: this.name,
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
                    error_message('err_name', 'permission.name', 'state_name');
                } else {
                    let response = res.data.data.saveAddPermission;
                    if (response.error) {
                        this.$swal({
                            title: 'Error',
                            text: response.message,
                            icon: 'error'
                        });
                    } else {
                        this.$emit('success');
                        this.$refs['add-permission'].hide();
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
