<template>
    <v-row>
        <v-col class="d-flex justify-center flex-column">
                <v-card class="mx-auto">
                    <v-card-title>
                        <v-toolbar class="bg-transparent">
                            <template v-slot:prepend>
                                <p class="text-h5">Remover</p>
                            </template>

                            <v-divider class="ms-3" inset vertical></v-divider>

                            <template v-slot:append>
                                <v-btn-group>
                                    <v-btn icon variant="plain" @click="close">
                                        <v-icon icon="fas fa-close"></v-icon>
                                    </v-btn>
                                </v-btn-group>
                            </template>
                        </v-toolbar>
                    </v-card-title>
                    <v-card-text>
                        <p class="text-h5">Do you want to remove this item permanently ? {{ delivery.name }}</p>
                    </v-card-text>
                

                <v-card-actions>
                    <v-btn-group>
                        <v-btn color="error"  variant="plain" size="x-small" @click="remove">
                            <v-icon icon="fas fa-trash"></v-icon>
                            Remove
                        </v-btn>
                        <v-btn variant="plain" size="x-small" @click="close">
                            <v-icon icon="fas fa-close"></v-icon>
                            cancel
                        </v-btn>
                    </v-btn-group>
                </v-card-actions>
            </v-card>
            
        </v-col>
    </v-row>
</template>

<script>
import axios from 'axios';

export default {
    props: ['delivery'],
    data: () => ({

    }),
    watch: {
        close(val) {
            val || this.close();
        }
    },
    methods: {
        close() {
            this.$emit('close-dialog');
        },
        remove() {
            axios.delete(`/delivery/delete/${this.delivery.id}`)
                .then((response) => {
                    this.close();
                    return this.$emit('remove', response);
                })
                .catch((response) => {
                    return alert('Error:' + response);
                });

            return this.$emit('remove', response);
        }
    }
}

</script>
