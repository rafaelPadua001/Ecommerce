<template>
     <v-dialog>
                <v-row>
                    <v-col>
                        <v-sheet>
                            <v-card>
                                <v-card-title>
                                    <v-toolbar>
                                        <v-toolbar-title>Remove Comment</v-toolbar-title>
                                        <template v-slot:append>
                                            <v-btn icon>
                                                <v-icon icon="fas fa-close" @click="close"></v-icon>
                                            </v-btn>
                                        </template>
                                    </v-toolbar>
                                </v-card-title>
                                <v-card-text>
                                    Remove your comment ? 
                                </v-card-text>

                                <v-card-actions>
                                    <v-btn variant="plain" @click="remove(comment)">
                                        Remove
                                    </v-btn>
                                    <v-btn variant="plain" @click="close">
                                        close
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-dialog>
</template>

<script>
export default {
    props: ['comment'],
    watch: {
        close(val){
            val || this.close();
        }
    },
    methods: {
        remove(item){
            axios.delete(`/api/comment/delete/${item.id}`)
            .then((response) => {
                this.$emit('close-dialog');
                return this.$emit('remove-comment', item);
            })
            .catch((response) => {
                alert('Error: ' + response);
            });
        },
        close(){
            this.$emit('close-dialog');
        }
    }
}
</script>