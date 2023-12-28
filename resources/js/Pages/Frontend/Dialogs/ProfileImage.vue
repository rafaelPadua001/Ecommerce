<template>
    <v-dialog width="auto">
        <v-row fluid justify="center">
            <v-col class="d-flex justify-center mb-6 flex-column" cols="auto" md="8">
                <v-card class="mx-auto text-center" :max-width="1800">
                    <v-card-title>
                        <v-toolbar color="transparent">
                            Profile image
                            <template v-slot:append>
                                <v-btn icon>
                                    <v-icon icon="fas fa-close" @click="closeUploadDialog"></v-icon>
                                </v-btn>
                            </template>

                        </v-toolbar>
                    </v-card-title>

                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="12">
                                <UploadInput @close-dialog="closeUploadDialog"/>
                            </v-col>

                        </v-row>

                    </v-card-text>

                    
                </v-card>
            </v-col>
        </v-row>
</v-dialog>
</template>

<script>
import UploadInput from '../Profile/profileImage/upload.vue';

export default {
    components: { UploadInput },
    data: () => ({

    }),
    watch: {
        closeUploadDialog(val) {
            return val || this.closeUploadDialog();
        }
    },
    methods: {
        closeUploadDialog() {
            this.$emit('close-dialog');
        }
    },
}
</script>