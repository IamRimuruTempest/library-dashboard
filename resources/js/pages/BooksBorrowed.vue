<template>
    <div>
        <h1>Borrowed Books</h1>
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
            <v-spacer></v-spacer>
            <div>
                <v-btn color="primary" elevation="0" dark @click="bookInsert()"
                    ><v-icon>mdi-plus</v-icon> Add Books</v-btn
                >
            </div>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">Book ID</th>
                            <th class="text-left">Title</th>
                            <th class="text-left">Author</th>
                            <th class="text-left">Publisher</th>
                            <th class="text-left">Category</th>
                            <th class="text-left">ISBN</th>
                            <th class="text-left">Shelf Number</th>
                            <th class="text-left">Status</th>
                            <th class="text-left"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </template>
            </v-simple-table>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
    components: {},
    data: () => ({}),

    methods: {},

    computed: {
        ...mapState(["allBooks"]),
    },

    mounted() {
        this.getBooks();
    },
};
</script>

<style lang="scss" scoped></style>
