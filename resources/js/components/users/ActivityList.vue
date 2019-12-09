<template>
    <div class="active tab-pane" id="activity">
        <ul class="panel-body list-group" style="height:330px;overflow-y:auto;">
            <li
                class="list-group-item"
                v-for="activity in activities.data"
                :key="activity.id"
            >
                <span style="color:#888;font-style:italic"
                    >{{ activity.created_at | DateWithTime }} |</span
                >
                <router-link :to="'/admin/profile/' + activity.user.id">{{
                    activity.user.name
                }}</router-link>
                {{ activity.subject }}
            </li>
            <li
                class="list-group-item"
                v-if="activities.data && activities.data.length == 0"
            >
                there is no activities
            </li>
        </ul>
    </div>
    <!-- /.tab-pane -->
</template>

<script>
import { mapGetters } from "vuex";

export default {
    props: {
        userId: {
            type: Number,
            required: true
        }
    },
    methods: {
        getlogActivityListsByClientId(id) {
            this.$store
                .dispatch("activity/getlogActivityListByClientId", id)
                .then(response => {})
                .catch(error => {
                    console.log(error);
                });
        }
    },
    created() {
        this.getlogActivityListsByClientId(this.userId);
    },
    computed: {
        ...mapGetters({
            activities: "activity/activityList"
        })
    }
};
</script>

<style></style>
