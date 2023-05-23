<template>
    <div>
        <v-form>
            <v-text-field label="Email" v-model="email"> </v-text-field>
            <v-text-field label="Password" v-model="password"> </v-text-field>
            <v-btn @click="login()">Login</v-btn>
        </v-form>
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
export default {
    data: () => ({
        email: null,
        password: null,
    }),
    methods: {
        ...mapActions(["getUsers", "storeUser"]),

        login() {
            const user = [];
            this.allUsers.filter((data) => {
                if (
                    data.email === this.email &&
                    data.password == this.password
                ) {
                    user.push(data);
                }
            });

            if (user.length != 0) {
                const newUser = {
                    id: user[0].id,
                    name: user[0].name,
                    email: user[0].email,
                };

                this.storeUser(newUser);
                this.$router.push("/dashboard");
            } else {
                console.log("wala pare, ");
            }
        },
    },

    computed: {
        ...mapState(["allUsers"]),
    },

    created() {
        this.getUsers();
    },

    mounted() {},
};
</script>
