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
                        @submit.prevent="InsertUpdateBook"
                        enctype="multipart/form-data"
                    >
                        <v-file-input
                            required
                            outlined
                            dense
                            persistent-placeholder
                            label="Book Cover (Recommed size: 400w - 800h)"
                            prepend-icon=""
                            prepend-inner-icon="mdi-clipboard-multiple-outline"
                            accept="image/png, image/jpeg, image/jpg"
                            ref="fileupload"
                            v-model="books.tmp_image"
                            @change="HandleImage($event)"
                            hide-details
                            class="pb-4"
                        >
                        </v-file-input>

                        <InputText
                            v-model="books.book_id"
                            label="Book Number"
                            v-mask="'############'"
                            required
                        />
                        <InputText v-model="books.title" label="Title" />
                        <InputText v-model="books.author" label="Author" />
                        <InputText
                            v-model="books.publisher"
                            label="Publisher"
                        />
                        <v-autocomplete
                            v-model="books.category"
                            label="Category"
                            :items="book_category"
                            multiple
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                        ></v-autocomplete>

                        <v-row class="mb-0">
                            <v-col class="pb-0 mt-0">
                                <InputText
                                    v-model="books.year"
                                    label="Year"
                                    v-mask="'####'"
                                />
                            </v-col>
                            <v-col class="pb-0 mt-0">
                                <InputText
                                    v-model="books.isbn"
                                    label="ISBN"
                                    v-mask="'###-#-##-######-#'"
                                />
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col class="pt-0">
                                <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    :return-value.sync="books.date_purchased"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="auto"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="books.date_purchased"
                                            label="Date Purchased"
                                            readonly
                                            outlined
                                            dense
                                            hide-details=""
                                            class="mb-3"
                                            clearable
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="date"
                                        no-title
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="menu = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            text
                                            color="primary"
                                            @click="$refs.menu.save(date)"
                                        >
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col class="pt-0">
                                <InputText
                                    v-model="books.price"
                                    label="Price"
                                />
                            </v-col>
                            <v-col class="pt-0">
                                <InputText
                                    v-model="books.shelf_no"
                                    label="Shelf Number"
                                    v-mask="'####'"
                                />
                            </v-col>
                        </v-row>

                        <v-autocomplete
                            v-model="books.status"
                            label="Status"
                            :items="['Available', 'Unavailable']"
                            clearable
                            hide-details
                            outlined
                            dense
                            class="pt-0 mb-3"
                        ></v-autocomplete>

                        <v-textarea
                            v-model="books.book_description"
                            outlined
                            auto-grow
                            rows="1"
                            row-height="15"
                            dense
                            label="Book Description"
                            hide-details
                            class="mb-3"
                        ></v-textarea>
                        <v-btn
                            color="primary"
                            block
                            elevation="0"
                            @click="InsertUpdateBook()"
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
        books: {
            id: null,
            image: null,
            book_id: null,
            title: null,
            author: null,
            publisher: null,
            category: null,
            year: null,
            isbn: null,
            date_purchased: null,
            price: null,
            shelf_no: null,
            status: null,
            books_description: null,
        },

        rules: [
            (value) => {
                if (value) return true;

                return "";
            },
        ],

        book_category: [
            "Fiction",
            "Non-fiction",
            "Mystery",
            "Thriller",
            "Romance",
            "Science fiction",
            "Fantasy",
            "Biography",
            "Autobiography",
            "History",
            "Memoir",
            "Self-help",
            "Business",
            "Travel",
            "Religion and spirituality",
            "Cookbooks",
            "Art and photography",
            "Children's books",
            "Young adult (YA) fiction",
            "Comics and graphic novels.",
        ],

        date: null,
        menu: false,
    }),

    methods: {
        HandleImage(event) {
            this.image = event;
            console.log(this.image);
        },

        InsertUpdateBook() {
            let formData = new FormData();
            const config = {
                headers: {
                    "content-type": "multipart/form-data",
                },
            };

            if (this.btn == "Save") {
                formData.append("image", this.image);
                formData.append("bookNum", this.books.book_id);
                formData.append("title", this.books.title);
                formData.append("author", this.books.author);
                formData.append("publisher", this.books.publisher);
                formData.append("category", this.books.category);
                formData.append("year", this.books.year);
                formData.append("isbn", this.books.isbn);
                formData.append("datePurchased", this.books.date_purchased);
                formData.append("price", this.books.price);
                formData.append("shelfNumber", this.books.shelf_no);
                formData.append("status", this.books.status);
                formData.append("description", this.books.book_description);

                axios
                    .post("/api/insert_book", formData, config)
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "You have successfully added a new book",
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        this.$emit("get-books");
                        this.showDialog = false;
                        this.image = null;
                        this.books = {};
                        // this.$refs.Insert.resetValidation();
                    })
                    .catch((error) => console.log("Error", error));
            } else {
                formData.append("id", this.books.id);
                formData.append("image", this.image);
                formData.append("bookNum", this.books.book_id);
                formData.append("title", this.books.title);
                formData.append("author", this.books.author);
                formData.append("publisher", this.books.publisher);
                formData.append("category", this.books.category);
                formData.append("year", this.books.year);
                formData.append("isbn", this.books.isbn);
                formData.append("datePurchased", this.books.date_purchased);
                formData.append("price", this.books.price);
                formData.append("shelfNumber", this.books.shelf_no);
                formData.append("status", this.books.status);
                formData.append("description", this.books.book_description);

                axios
                    .post("/api/update_book", formData, config)
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "Your changes have been successfully saved!!",
                            showConfirmButton: false,
                            timer: 2500,
                        });
                        this.$emit("get-books");
                        this.showDialog = false;
                        this.image = null;
                        this.books = {};

                        // this.$refs.Insert.resetValidation();
                    })
                    .catch((error) => console.log(error));
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
                this.books.id = this.items.id;
                this.image = this.items.book_image;
                this.books.book_id = this.items.book_id;
                this.books.title = this.items.title;
                this.books.author = this.items.author;
                this.books.publisher = this.items.publisher;
                this.books.category = this.items.category;
                this.books.year = this.items.year;
                this.books.isbn = this.items.isbn;
                this.books.date_purchased = this.items.date_purchased;
                this.books.price = this.items.price;
                this.books.shelf_no = this.items.shelf_no;
                this.books.status = this.items.status;
                this.books.book_description = this.items.book_description;
            }
        },
    },
};
</script>

<style lang="scss" scoped></style>
