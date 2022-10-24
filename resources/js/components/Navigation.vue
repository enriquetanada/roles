<template>
    <div id="app">
        <b-navbar class="px-3" toggleable="lg" type="dark" variant="dark">
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav class="me-right">
                    <b-nav-item> 
                        <router-link class="nav-link" :to="{name: 'dashboard'}">Dashboard</router-link>
                    </b-nav-item>
                    <b-nav-item v-if="withPermission('user_view')"> 
                        <router-link class="nav-link" :to="{name: 'users'}">User</router-link>
                    </b-nav-item>
                    <b-nav-item v-if="withPermission('role_view')"> 
                        <router-link class="nav-link" :to="{name: 'role'}">Role</router-link>
                    </b-nav-item>
                    <b-nav-item v-if="withPermission('permission_view')"> 
                        <router-link class="nav-link" :to="{name: 'permission'}">Permission</router-link>
                    </b-nav-item>
                    <b-nav-item @click="onLogout">
                        <router-link class="nav-link" :to="{name: 'login'}">Sign Out</router-link>
                    </b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
export default {
    data() {
        return {
            member: '',
        };
    },
    created() {
        this.onCheckUserLoggedIn();
    },
    methods: {
        onLogout() {
            this.$store.commit('resetState');
            this.$appEvents.$emit('user-logout');
            this.$router.push({ name: 'login' });
        },
        onCheckUserLoggedIn() {
            if (this.$store.state.userApiToken) {
                this.$query('getUser').then((res) => {
                    this.member = res.data.data.getUser;
                });
                return true;
            } else {
                return false;
            }
        },
    },
};
</script>
