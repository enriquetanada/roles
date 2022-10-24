<template>
    <div>
        <div class="container mt-5">
            <b-card class="text-center">
                <h1 class="whiz-error-head">404</h1>
                <h3 class="nk-error-title whiz-error">Oops! Why you’re here?</h3>
                <p class="nk-error-text whiz-error">
                        We are very sorry for inconvenience. It looks like you’re try to access a page that either has been deleted or never existed.
                </p>
                <b-button @click="onCheckRoute" class="btn"> Back To Home</b-button>
            </b-card>
        </div>
    </div>
</template>

<script>
    export default {
        methods:{
            onCheckRoute(){
                if(this.$store.state.userApiToken) {
                    this.$router.push({ name: 'dashboard' });
                } else {
                    this.$router.push({ name: 'login' });
                }
            }
        }
    }
</script>