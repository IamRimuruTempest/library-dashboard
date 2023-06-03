<template>
    <div
        style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        "
    >
        <v-card width="600" class="pa-10">
            <div class="text-center">
                <img
                    src="../../../public/images/logo.png"
                    width="150px"
                    height="100%"
                    alt=""
                />
            </div>
            <v-form @submit.prevent="login" ref="form">
                <v-text-field
                    label="Email"
                    v-model="email"
                    outlined
                    :rules="rules"
                >
                </v-text-field>
                <v-text-field
                    label="Password"
                    v-model="password"
                    outlined
                    :append-icon="eye ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="toggleEye"
                    :type="eye != true ? 'text' : 'password'"
                    :rules="rules"
                >
                </v-text-field>
                <v-btn
                    block
                    tile
                    elevation="0"
                    large
                    @click="login()"
                    color="primary"
                    >Login</v-btn
                >
            </v-form>
        </v-card>
    </div>
</template>
<script>
import { mapActions, mapState } from "vuex";
export default {
    data: () => ({
        email: null,
        password: null,

        eye: true,

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],
    }),
    methods: {
        ...mapActions(["getUsers", "storeUser"]),

        toggleEye() {
            this.eye = !this.eye;
        },

        login() {
            if (this.$refs.form.validate()) {
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
                    this.$swal({
                        icon: "success",
                        title: "Hoorayyy!",
                        text: "You have successfully logged in!",
                        showConfirmButton: false,
                        timer: 4000,
                    });
                } else {
                    this.$swal({
                        icon: "warning",
                        title: "Incorrect Email or Password",
                        text: "Your email or password is incorrect or this account doesn't exist.",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                    return false;
                }
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
