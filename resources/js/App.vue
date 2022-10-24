<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: '',
            userLoggedIn: this.onCheckUserLoggedIn(),
        };
    },
    created() {
      this.$appEvents.$on('user-login', () => {
          this.userLoggedIn = true;
      });

      this.$appEvents.$on('user-logout', () => {
          this.userLoggedIn = false;
      });
    },
    methods: {
        onCheckUserLoggedIn() {
            if (this.$store.state.userApiToken) {
                this.$query('getUser').then((res) => {
                    this.user = res.data.data.getUser;
                });
                return true;
            } else {
                return false;
            }
        }
    },
};
</script>
