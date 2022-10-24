import Vue from 'vue';

Vue.mixin({
    methods: {
        showEntries(page, current, perPage, rows) {
            let current_page =
                rows === 0
                    ? 0
                    : current === 1
                    ? 1
                    : current * page + 1 - perPage;
            return `${current_page} - ${
                rows < current * page ? rows : current * page
            } of ${rows} entries`;
        },
        withPermission(permission) {
            if (this.$store.state.permissions.includes(permission)) {
                return true;
            }
            return false;
        }
    },
});
