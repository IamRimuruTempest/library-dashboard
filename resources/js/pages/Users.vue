<template>
    <div>
        <h1>Users</h1>
        <div class="d-flex align-center">
            <v-row>
                <v-col cols="5" class="pr-0">
                    <v-text-field
                        v-model="search"
                        placeholder="Search User"
                        outlined
                        dense
                        hide-details=""
                        clearable
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-spacer></v-spacer>
            <div>
                <v-btn
                    color="primary"
                    elevation="0"
                    dark
                    @click="userInsertDialog = true"
                    ><v-icon>mdi-plus</v-icon> Add User</v-btn
                >
            </div>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-left">Student ID</th>
                            <th class="text-left">Fullname</th>
                            <th class="text-left">Department</th>
                            <th class="text-left">Course</th>
                            <th class="text-left">Year</th>
                            <th class="text-left">Gender</th>
                            <th class="text-left">Contact Number</th>
                            <th class="text-left">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredUsers" :key="item.id">
                            <td>
                                <v-avatar v-if="item.image != null">
                                    <img
                                        width="50px"
                                        height="100%"
                                        :src="`/profiles/${item.image}`"
                                    />
                                </v-avatar>
                                <v-avatar v-else color="indigo">
                                    <v-icon dark> mdi-account-circle </v-icon>
                                </v-avatar>
                            </td>
                            <td>{{ item.student_id }}</td>
                            <td>
                                {{ item.first_name }} {{ item.middle_name }}
                                {{ item.last_name }} {{ item.suffix }}
                            </td>
                            <td>{{ item.department }}</td>
                            <td>
                                {{ item.course }}
                            </td>
                            <td>{{ item.year }}</td>
                            <td>{{ item.gender }}</td>
                            <td>{{ item.phone_number }}</td>
                            <td>
                                <v-chip
                                    :color="
                                        item.status == 'Registered'
                                            ? 'green'
                                            : 'red'
                                    "
                                    dark
                                    >{{ item.status }}</v-chip
                                >
                            </td>

                            <td>
                                <v-btn icon color="blue">
                                    <v-icon>mdi-eye-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="updateUserMethod(item)"
                                >
                                    <v-icon>mdi-pencil-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="deleteUserMethod(item)"
                                >
                                    <v-icon>mdi-delete-circle-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
        <UsersDialog
            v-model="userInsertDialog"
            title="Insert User"
            btn="Save"
            @get-users="getUsers()"
        />

        <UsersDialog
            v-model="userUpdateDialog"
            title="Update User"
            btn="Update"
            :items="userInformation"
            @get-users="getUsers()"
        />
    </div>
</template>

<script>
import axios from "axios";
import UsersDialog from "../components/Dialogs/UsersDialog.vue";
export default {
    components: {
        UsersDialog,
    },
    data: () => ({
        userInsertDialog: false,
        userUpdateDialog: false,
        userInfoDialog: false,

        users: [],
        userInformation: null,

        search: "",
    }),

    methods: {
        getUsers() {
            axios({
                method: "post",
                url: "/api/accounts",
            }).then((res) => {
                console.log(res.data, "test");
                this.users = res.data;
            });
        },

        updateUserMethod(item) {
            this.userInformation = item;
            this.userUpdateDialog = true;
        },

        deleteUserMethod(item) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: "post",
                        url: "/api/delete_user",
                        data: item,
                    }).then((res) => {
                        this.$swal(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.getUsers();
                    });
                }
            });
        },
    },

    computed: {
        filteredUsers() {
            if (this.search) {
                return this.users.filter((element) => {
                    return (
                        element.first_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.middle_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.last_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.student_id
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                    );
                });
            } else {
                return this.users;
            }
        },
    },

    created() {
        this.getUsers();
    },
};
</script>

<style lang="scss" scoped></style>
