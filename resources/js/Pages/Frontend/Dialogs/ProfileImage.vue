<template>
    <v-dialog
        transition="dialog-top-transition"
    >
        <v-row fluid justify="center">
            <v-col class="d-flex justify-center flex-column" cols="auto">
                <v-card class="mx-auto text-center" :width="400">
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
                                <UploadInput
                                    @close-dialog="closeUploadDialog"
                                    @update-avatar="updateAvatar"/>
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
    emits: ['update-avatar', 'close-dialog'],
    data: () => ({
        image: [],
    }),
    watch: {
        closeUploadDialog(val) {
            return val || this.closeUploadDialog();
        }
    },
    methods: {
        closeUploadDialog() {
            return this.$emit('close-dialog');
        },
        updateAvatar(response){
         return this.$emit('update-avatar', response);
        }
    },
}
</script>