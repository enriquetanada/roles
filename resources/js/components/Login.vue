<template>
      <section class="vh-100">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-xl-10">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-6 col-lg-5 d-none d-md-block">
                                <img 
                                    src="https://www.marketing91.com/wp-content/uploads/2019/07/Importance-of-Corporate-Image.jpg" 
                                    alt="signin form" 
                                    class="img-fluid"/>
                            </div>
                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">
                                    <b-form @submit.prevent="onSubmitForm">
                                        <h1 class="h1 fw-bold mb-0 mb-3">User Role</h1>
                                        <h5 class="signin-label mb-3 pb-3" style="letter-spacing: 1px">Sign into your account</h5>
                                        <b-form-group class="mb-3">
                                            <b-form-input
                                                v-model="email"
                                                type="text"
                                                class="form-control form-control-lg"
                                                placeholder="Enter email"
                                                :state="email_state"
                                                trim>
                                            </b-form-input>
                                            <b-form-invalid-feedback>{{ email_error }}</b-form-invalid-feedback>
                                        </b-form-group>
                                        <b-form-group class="mb-3">
                                             <b-form-input
                                                v-model="password"
                                                type="password"
                                                class="form-control form-control-lg"
                                                placeholder="Enter Password"
                                                autocomplete="off"
                                                :state="password_state">
                                            </b-form-input>
                                            <b-form-invalid-feedback>{{ password_error }}</b-form-invalid-feedback>
                                        </b-form-group>
                                        <div class="d-grid pt-1 mb-3">
                                            <b-form-group>
                                                <b-button
                                                    v-if="!isSaving"
                                                    class="btn btn-dark btn-lg btn-block"
                                                    type="submit">
                                                Sign in
                                                </b-button>
                                                <b-button
                                                    v-else
                                                    class="btn btn-dark btn-lg btn-block"
                                                    disabled>
                                                    <b-spinner small type="grow"></b-spinner>
                                                    Signing In
                                                </b-button>
                                            </b-form-group>
                                        </div>
                                        <p class="account-registration mb-5">
                                            Don't have an account?
                                            <router-link :to="{name: 'registration'}">Signup here</router-link>
                                        </p>
                                    </b-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</template>

<script>
    export default {

        data() {
            return {
                email: "",
                password: "",

                email_error: '',
                password_error: '',

                email_state: null,
                password_state: null,

                isSaving: false
            }
        },
        methods: {
            onClearErrors() {
                this.email_error = '';
                this.password_error = '';
                this.email_state = null;
                this.password_state = null;
            },
            onSubmitForm() {
                this.isSaving = true;
                this.onClearErrors();
                this.$query('saveLogin', {
                    user: {
                        email: this.email,
                        password: this.password
                    },
                }).then((res) => {
                    this.isSaving = false;

                    if (res.data.errors) {
                        let errors = Object.values(res.data.errors[0].extensions.validation).flat();
                        let errors_keys = Object.keys(res.data.errors[0].extensions.validation).flat();

                        const error_message = (name, index, state) => {
                            this[name] = errors_keys.some((q) => q == index)
                                ? errors[errors_keys.indexOf(index)]
                                : '';

                            if (this[name]) this[state] = false;
                        };

                        error_message('email_error', 'user.email', 'email_state');
                        error_message('password_error','user.password','password_state');

                    } else {
                        let response = res.data.data.saveLogin;
                        if (response.error) {
                            this.$swal({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        } else {
                            this.$store.commit('setLogin', response);
                            this.$store.commit('setPermission', response.permissions);
                            this.$router.push({ name: 'dashboard' });
                        }
                        
                    }
                });
            }
        }
    }
</script>