<template>
    <div>
        <v-dialog v-model="showDialog" width="600" persistent>
            <v-card>
                <!-- <v-card-title class="text-h5 grey lighten-2">
                    Add Books
                </v-card-title> -->
                <v-toolbar class="mb-1" elevation="0">
                    <v-toolbar-title class="ml-2">{{ title }}</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn icon @click="showDialog = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-text>
                    <v-form
                        @submit.prevent="InsertUpdateEvent"
                        enctype="multipart/form-data"
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
                            v-model="user.image"
                            @change="HandleImage($event)"
                            hide-details
                            class="pb-4"
                        >
                        </v-file-input>

                        <InputText
                            v-model="user.student_id"
                            label="Student ID"
                        />
                        <InputText
                            v-model="user.first_name"
                            label="First Name"
                        />
                        <InputText
                            v-model="user.middle_name"
                            label="Middle Name"
                        />
                        <InputText v-model="user.last_name" label="Last Name" />
                        <InputText v-model="user.suffix" label="Suffix" />

                        <InputText
                            v-model="user.department"
                            label="Department"
                        />
                        <InputText v-model="user.course" label="Course" />
                        <v-row class="mb-0">
                            <v-col class="pb-0 mt-0">
                                <InputText v-model="user.year" label="Year" />
                            </v-col>
                            <v-col class="pb-0 mt-0">
                                <InputText
                                    v-model="user.gender"
                                    label="Gender"
                                />
                            </v-col>
                        </v-row>

                        <InputText
                            v-model="user.phone_number"
                            label="Contact Number"
                        />
                        <InputText
                            v-if="btn == 'Update'"
                            v-model="user.username"
                            label="Username"
                        />

                        <InputText
                            v-if="btn == 'Update'"
                            v-model="user.password"
                            label="Password"
                        />

                        <v-btn
                            color="primary"
                            block
                            elevation="0"
                            @click="InsertUpdateUser()"
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
        user: {
            id: null,
            student_id: null,
            first_name: null,
            middle_name: null,
            last_name: null,
            suffix: null,
            department: null,
            course: null,
            year: null,
            gender: null,
            phone_number: null,
            image: null,
            username: null,
            password: null,
        },

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],
    }),

    methods: {
        HandleImage(event) {
            this.image = event;
            console.log(this.image);
        },

        InsertUpdateUser() {
            let formData = new FormData();
            const config = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };
            if (this.btn == "Save") {
                formData.append("student_id", this.user.student_id);
                formData.append("first_name", this.user.first_name);
                formData.append("middle_name", this.user.middle_name);
                formData.append("last_name", this.user.last_name);
                formData.append("suffix", this.user.suffix);
                formData.append("department", this.user.department);
                formData.append("course", this.user.course);
                formData.append("year", this.user.year);
                formData.append("gender", this.user.gender);
                formData.append("phone_number", this.user.phone_number);
                formData.append("image", this.image);
                axios
                    .post("/api/insert_user", formData, config)
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "You have successfully added new user!",
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        this.$emit("get-users");
                        this.showDialog = false;
                        this.image = null;
                        this.user = {};
                        // this.$refs.Insert.resetValidation();
                    })
                    .catch((error) => console.log("Error", error));
            } else {
                formData.append("id", this.user.id);
                formData.append("student_id", this.user.student_id);
                formData.append("first_name", this.user.first_name);
                formData.append("middle_name", this.user.middle_name);
                formData.append("last_name", this.user.last_name);
                formData.append("suffix", this.user.suffix);
                formData.append("department", this.user.department);
                formData.append("course", this.user.course);
                formData.append("year", this.user.year);
                formData.append("gender", this.user.gender);
                formData.append("phone_number", this.user.phone_number);
                formData.append("username", this.user.username);
                formData.append("password", this.user.password);
                formData.append("image", this.image);
                axios
                    .post("/api/update_user", formData, config)
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "You have successfully updated user!",
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        this.$emit("get-users");
                        this.showDialog = false;
                        this.image = null;
                        this.user = {};
                        // this.$refs.Insert.resetValidation();
                    })
                    .catch((error) => console.log("Error", error));
            }
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
            if (this.btn != "Save") {
                console.log(this.items);
                this.user.id = this.items.id;
                this.user.student_id = this.items.student_id;
                this.user.first_name = this.items.first_name;
                this.user.middle_name = this.items.middle_name;
                this.user.last_name = this.items.last_name;
                this.user.suffix = this.items.suffix;
                this.user.department = this.items.department;
                this.user.course = this.items.course;
                this.user.year = this.items.year;
                this.user.gender = this.items.gender;
                this.user.phone_number = this.items.phone_number;
                this.user.username = this.items.username;
                this.user.password = this.items.password;
                this.image = this.items.image;
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
