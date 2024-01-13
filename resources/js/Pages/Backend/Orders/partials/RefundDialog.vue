<template>
    <v-dialog>
        <v-row no-gutters>
            <v-col class="d-flex justify-center flex-column">
                <v-sheet>
                    <v-card class="mx-auto">
                        <v-card-text>
                            Extorno realizado com sucesso.
                            {{ message }}
                        </v-card-text>

                        <v-card-actions>
                            <v-btn class="me-2" color="primary" variant="flat" block @click="close()">Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-sheet>
            </v-col>
        </v-row>
    </v-dialog>

</template>

<script>
    export default{
        props: ['message'],
        methods: {
            close(){
                return this.$emit('close-dialog');
            }
        }
    }
</script>