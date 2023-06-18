<template>
    <div>
        <h1>Reserved Books</h1>
        <div class="d-flex align-center">
            <v-row>
                <v-col cols="5" class="pr-0">
                    <v-text-field
                        placeholder="Search Books"
                        outlined
                        dense
                        hide-details=""
                        clearable
                        v-model="search"
                    ></v-text-field>
                </v-col>
            </v-row>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">ISBN</th>
                            <th class="text-left">Book</th>
                            <th class="text-left">Borrower</th>
                            <th class="text-left">Department</th>
                            <th class="text-left">Course</th>
                            <th class="text-left">Year Level</th>
                            <th class="text-left">Phone Number</th>
                            <th class="text-left">Date Reserved</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in filteredReservedBooks"
                            :key="item.rid"
                        >
                            <td>{{ item.isbn }}</td>
                            <td>{{ item.title }}</td>
                            <td>
                                {{ item.first_name }} {{ item.middle_name }}
                                {{ item.last_name }} {{ item.suffix }}
                            </td>
                            <td>{{ item.department }}</td>
                            <td>{{ item.course }}</td>
                            <td>{{ item.year }}</td>
                            <td>{{ item.phone_number }}</td>
                            <td>{{ item.reserved_date }}</td>

                            <td>
                                <v-btn
                                    @click="
                                        insertToBorrowedBooks(
                                            item.rid,
                                            item.isbn,
                                            item.student_id
                                        )
                                    "
                                    icon
                                    color="blue"
                                >
                                    <v-icon>mdi-check-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    @click="cancelReservations(item.rid)"
                                    icon
                                    color="red"
                                >
                                    <v-icon>mdi-close-circle-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import { mapActions, mapState } from "vuex";
export default {
    components: {},
    data: () => ({
        search: "",
        reservedBooks: [],
    }),

    methods: {
        getReservedBooks() {
            axios({
                method: "post",
                url: "/api/get_reserved_books",
            }).then((res) => {
                console.log(res.data);
                this.reservedBooks = res.data;
            });
        },

        insertToBorrowedBooks(rid, isbn, student_id) {
            const admin = this.loggedInUser.name;
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, approve it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: "post",
                        url: "/api/approved_reservation",
                        data: {
                            rid: rid,
                            isbn: isbn,
                            student_id: student_id,
                            admin: admin,
                        },
                    }).then((res) => {
                        this.$swal(
                            "Approved!",
                            "You've successfully approved the reservation.",
                            "success"
                        );
                        console.log(res.data);
                        this.getReservedBooks();
                    });
                }
            });
        },

        cancelReservations(rid) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, cancel it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: "post",
                        url: "/api/cancel_reservations",
                        data: {
                            rid: rid,
                        },
                    }).then((res) => {
                        this.$swal(
                            "Canceled!",
                            "You've successfully canceled the reservation.",
                            "success"
                        );
                        this.getReservedBooks();
                    });
                }
            });
        },
    },

    computed: {
        ...mapState(["loggedInUser"]),
        filteredReservedBooks() {
            if (this.search) {
                return this.reservedBooks.filter((element) => {
                    return (
                        element.title
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.first_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.last_name
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.student_id
                            .toUpperCase()
                            .includes(this.search.toUpperCase()) ||
                        element.isbn
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                    );
                });
            } else {
                return this.reservedBooks;
            }
        },
    },

    mounted() {
        this.getReservedBooks();
    },
};
</script>

<style lang="scss" scoped></style>
