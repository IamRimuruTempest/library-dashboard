<template>
    <div>
        <h1>Library Staff</h1>
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
                    v-if="checkRole == 'Super Admin'"
                    @click="adminInsertDialog = true"
                    color="primary"
                    elevation="0"
                    dark
                    ><v-icon>mdi-plus</v-icon> Add Staff</v-btn
                >
            </div>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <td></td>
                            <th class="text-left">Employee ID</th>
                            <th class="text-left">Fullname</th>
                            <th class="text-left">Age</th>
                            <th class="text-left">Gemder</th>
                            <th class="text-left">Phone Number</th>
                            <th class="text-left">Email</th>
                            <th v-if="checkRole == 'Super Admin'"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredAdmin" :key="item.id">
                            <td>
                                <v-avatar v-if="item.image != null">
                                    <img
                                        width="50px"
                                        height="100%"
                                        :src="`/admins/${item.image}`"
                                    />
                                </v-avatar>
                                <v-avatar v-else color="indigo">
                                    <v-icon dark> mdi-account-circle </v-icon>
                                </v-avatar>
                            </td>
                            <td>{{ item.employee_id }}</td>
                            <td>
                                {{ item.name }}
                            </td>
                            <td>
                                {{ item.age }}
                            </td>
                            <td>
                                {{ item.gender }}
                            </td>
                            <td>
                                {{ item.phone_number }}
                            </td>
                            <td>
                                {{ item.email }}
                            </td>

                            <td v-if="checkRole == 'Super Admin'">
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="updateAdminMethod(item)"
                                >
                                    <v-icon>mdi-pencil-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="deleteAdminMethod(item)"
                                >
                                    <v-icon>mdi-delete-circle-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>

        <AdminDialog
            v-model="adminInsertDialog"
            title="Insert Admin"
            btn="Save"
            @get-admins="getAdmins()"
        />

        <AdminDialog
            v-model="adminUpdateDialog"
            title="Update Admin"
            btn="Update"
            :items="adminInformation"
            @get-admins="getAdmins()"
        />
    </div>
</template>

<script>
import axios from "axios";
import AdminDialog from "../components/Dialogs/AdminDialog.vue";
import { mapState } from "vuex";
export default {
    components: {
        AdminDialog,
    },
    data: () => ({
        adminInsertDialog: false,
        adminUpdateDialog: false,
        adminInfoDialog: false,

        admins: [],
        adminInformation: null,

        search: "",
    }),

    methods: {
        getAdmins() {
            axios({
                method: "post",
                url: "/api/getUsers",
            }).then((res) => {
                console.log(res.data, "test");
                this.admins = res.data;
            });
        },

        updateAdminMethod(item) {
            this.adminInformation = item;
            this.adminUpdateDialog = true;
        },

        deleteAdminMethod(item) {
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
                        url: "/api/delete_admin",
                        data: item,
                    }).then((res) => {
                        this.$swal(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.getAdmins();
                    });
                }
            });
        },
    },

    computed: {
        ...mapState(["loggedInUser"]),

        checkRole() {
            return this.loggedInUser.role;
        },

        filteredAdmin() {
            if (this.search) {
                return this.admins.filter((element) => {
                    return (
                        element.name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.email
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.employee_id
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                    );
                });
            } else {
                return this.admins;
            }
        },
    },

    created() {
        this.getAdmins();
    },
};
</script>

<style lang="scss" scoped></style>
