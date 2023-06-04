<template>
    <div>
        <v-dialog v-model="showDialog" width="800" persistent>
            <v-card>
                <!-- <v-card-title class="text-h5 grey lighten-2">
                    Add Books
                </v-card-title> -->
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2">{{ title }}</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="closeDialog()">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form
                        @submit.prevent="InsertUpdateAdmin"
                        enctype="multipart/form-data"
                        ref="form"
                    >
                        <v-file-input
                            required
                            outlined
                            dense
                            persistent-placeholder
                            label="Event Cover (Recommed size: 400w - 800h)"
                            prepend-icon=""
                            prepend-inner-icon="mdi-clipboard-multiple-outline"
                            accept="image/png, image/jpeg, image/jpg"
                            ref="fileupload"
                            v-model="admin.image"
                            @change="HandleImage($event)"
                            hide-details
                            class="pb-4"
                        >
                        </v-file-input>

                        <InputText
                            v-model="admin.employee_id"
                            label="Student ID"
                            v-mask="'##-######'"
                            :rules="rules"
                        />

                        <InputText
                            v-model="admin.fullname"
                            label="Full Name"
                            :rules="rules"
                        />

                        <v-row class="mb-0 pt-0">
                            <v-col class="pb-0 pt-0 mt-0">
                                <v-autocomplete
                                    v-model="admin.gender"
                                    label="Gender"
                                    :items="['Male', 'Female']"
                                    clearable
                                    hide-details
                                    outlined
                                    dense
                                    class="pt-0 mb-3"
                                    :rules="rules"
                                ></v-autocomplete>
                            </v-col>
                            <v-col class="pb-0 pt-0 mt-0">
                                <InputText
                                    v-model="admin.age"
                                    label="Age"
                                    v-mask="'##'"
                                    :rules="rules"
                                />
                            </v-col>
                            <v-col class="pb-0 pt-0 mt-0">
                                <InputText
                                    v-model="admin.phone_number"
                                    label="Contact Number"
                                    v-mask="'+63-###-###-####'"
                                    :rules="rules"
                                />
                            </v-col>
                        </v-row>

                        <InputText
                            v-model="admin.email"
                            label="Email"
                            :rules="rules"
                        />

                        <InputText
                            v-model="admin.password"
                            label="Password"
                            :rules="rules"
                        />

                        <v-autocomplete
                            v-model="admin.role"
                            :rules="rules"
                            label="Status"
                            :items="['Super Admin', 'Admin']"
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                        ></v-autocomplete>

                        <v-btn
                            color="primary"
                            block
                            elevation="0"
                            @click="InsertUpdateAdmin()"
                        >
                            {{ btn }}</v-btn
                        ></v-form
                    >
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import InputText from "../InputText.vue";
import axios from "axios";
export default {
    props: ["value", "items", "title", "btn"],
    components: {
        InputText,
    },
    data: () => ({
        image: null,
        admin: {
            id: null,
            employee_id: null,
            fullname: null,
            gender: null,
            age: null,
            phone_number: null,
            image: null,
            email: null,
            password: null,
            role: null,
        },

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],

        eye: true,
    }),

    methods: {
        HandleImage(event) {
            this.image = event;
            console.log(this.image);
        },

        InsertUpdateAdmin() {
            if (this.$refs.form.validate()) {
                let formData = new FormData();
                const config = {
                    headers: {
                        "content-type": "multipart/form-data",
                    },
                };
                if (this.btn == "Save") {
                    formData.append("employee_id", this.admin.employee_id);
                    formData.append("full_name", this.admin.fullname);
                    formData.append("gender", this.admin.gender);
                    formData.append("age", this.admin.age);
                    formData.append("phone_number", this.admin.phone_number);
                    formData.append("email", this.admin.email);
                    formData.append("password", this.admin.password);
                    formData.append("role", this.admin.role);
                    formData.append("image", this.image);
                    axios
                        .post("/api/insert_admin", formData, config)
                        .then((res) => {
                            this.$swal({
                                icon: "success",
                                title: "You have successfully added new admin!",
                                showConfirmButton: false,
                                timer: 2500,
                            });
                            // this.$refs.form.reset();
                            this.$emit("get-admins");
                            this.showDialog = false;
                            this.admin = {};
                            this.image = null;
                            // this.$refs.Insert.resetValidation();
                        })
                        .catch((error) => console.log("Error", error));
                } else {
                    formData.append("id", this.admin.id);
                    formData.append("employee_id", this.admin.employee_id);
                    formData.append("full_name", this.admin.fullname);
                    formData.append("gender", this.admin.gender);
                    formData.append("age", this.admin.age);
                    formData.append("phone_number", this.admin.phone_number);
                    formData.append("email", this.admin.email);
                    formData.append("password", this.admin.password);
                    formData.append("role", this.admin.role);
                    formData.append("image", this.image);
                    axios
                        .post("/api/update_admin", formData, config)
                        .then((res) => {
                            this.$swal({
                                icon: "success",
                                title: "You have successfully updated an admin!",
                                showConfirmButton: false,
                                timer: 2500,
                            });
                            // this.$refs.form.reset();
                            this.$emit("get-admins");
                            this.showDialog = false;
                            this.admin = {};
                            this.image = null;
                            // this.$refs.Insert.resetValidation();
                        })
                        .catch((error) => console.log("Error", error));
                }
            }
        },

        closeDialog() {
            // this.$refs.form.reset();
            this.showDialog = false;
        },
    },

    computed: {
        showDialog: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit("input", value);
            },
        },
    },

    watch: {
        showDialog() {
            if (this.btn == "Update") {
                console.log(this.items);
                this.admin.id = this.items.id;
                this.admin.employee_id = this.items.employee_id;
                this.admin.fullname = this.items.first_name;
                this.admin.gender = this.items.gender;
                this.admin.age = this.items.age;
                this.admin.phone_number = this.items.phone_number;
                this.admin.fullname = this.items.name;
                this.admin.email = this.items.email;
                this.admin.password = this.items.password;
                this.admin.role = this.items.role;
                this.image = this.items.image;
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
