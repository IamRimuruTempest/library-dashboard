<template>
    <div>
        <h1>Events</h1>
        <div class="d-flex align-center">
            <v-row>
                <v-col cols="5" class="pr-0">
                    <v-text-field
                        placeholder="Search Events"
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
                    @click="eventInsertDialog = true"
                    ><v-icon>mdi-plus</v-icon> Add Events</v-btn
                >
            </div>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-left">Event</th>
                            <th class="text-left">Subtitle</th>
                            <th class="text-left">Location</th>
                            <th class="text-left">Date</th>
                            <th class="text-left">Time</th>
                            <th class="text-left">Status</th>
                            <!-- <th class="text-left">Description</th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in events" :key="item.id">
                            <td>{{ item.title }}</td>
                            <td>{{ item.subtitle }}</td>
                            <td>{{ item.location }}</td>
                            <td>
                                {{ item.date }}
                            </td>
                            <td>{{ item.time }}</td>
                            <td>{{ item.status }}</td>
                            <!-- <td>{{ item.description }}</td> -->
                            <td>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="showEventMethod(item)"
                                >
                                    <v-icon>mdi-eye-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="updateEventMethod(item)"
                                >
                                    <v-icon>mdi-pencil-circle-outline</v-icon>
                                </v-btn>
                                <v-btn
                                    icon
                                    color="blue"
                                    @click="deleteEventMethod(item)"
                                >
                                    <v-icon>mdi-delete-circle-outline</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
        <EventsInfoDialog v-model="eventInfoDialog" :items="eventInformation" />

        <EventsDialog
            v-model="eventInsertDialog"
            title="Add Events"
            btn="Save"
            @get-events="getEvents()"
        />

        <EventsDialog
            v-model="eventUpdateDialog"
            title="Update Event"
            btn="Update"
            :items="eventInformation"
            @get-events="getEvents()"
        />
    </div>
</template>

<script>
import axios from "axios";
import EventsDialog from "../components/Dialogs/EventsDialog.vue";
import EventsInfoDialog from "../components/Dialogs/EventsInfoDialog.vue";
export default {
    components: {
        EventsDialog,
        EventsInfoDialog,
    },
    data: () => ({
        eventInsertDialog: false,
        eventUpdateDialog: false,
        eventInfoDialog: false,

        events: [],
        eventInformation: null,
    }),

    methods: {
        getEvents() {
            axios({
                method: "post",
                url: "/api/events",
            }).then((res) => {
                console.log(res.data, "test test");
                this.events = res.data;
            });
        },

        showEventMethod(item) {
            this.eventInfoDialog = true;
            this.eventInformation = item;
        },

        updateEventMethod(item) {
            this.eventUpdateDialog = true;
            this.eventInformation = item;
        },

        deleteEventMethod(item) {
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
                        url: "/api/delete_event",
                        data: item,
                    }).then((res) => {
                        this.$swal(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.getEvents();
                        console.log(res.data);
                    });
                }
            });
        },
    },

    created() {
        this.getEvents();
    },
};
</script>

<style lang="scss" scoped></style>
