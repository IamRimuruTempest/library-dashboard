<template>
    <div>
        <h1>Returned Books</h1>
        <div class="d-flex align-center">
            <v-row>
                <v-col cols="5" class="pr-0">
                    <v-text-field
                        placeholder="Search Books"
                        outlined
                        dense
                        hide-details=""
                        clearable
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
                            <th class="text-left">Borrowers ID</th>
                            <th class="text-left">Borrower</th>
                            <th class="text-left">Course</th>
                            <th class="text-left">Phone Number</th>
                            <th class="text-left">Receiver</th>
                            <th class="text-left">Date Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in booksReturned" :key="item.id">
                            <td>{{ item.isbn }}</td>
                            <td>{{ item.title }}</td>
                            <td>{{ item.student_id }}</td>
                            <td>
                                {{ item.first_name }} {{ item.middle_name }}
                                {{ item.last_name }} {{ item.suffix }}
                            </td>
                            <td>{{ item.course }}</td>
                            <td>{{ item.phone_number }}</td>
                            <td>{{ item.receiver }}</td>
                            <td>{{ item.date_received }}</td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
    </div>
</template>

<script>
import axios from "axios";

import { mapActions, mapState } from "vuex";
export default {
    data: () => ({
        booksReturned: [],
    }),
    methods: {
        getReturnedBooks() {
            axios({
                method: "post",
                url: "/api/get_returned_books",
            }).then((res) => {
                console.log(res.data);
                this.booksReturned = res.data;
            });
        },
    },

    created() {
        this.getReturnedBooks();
    },
};
</script>

<style lang="scss" scoped></style>
