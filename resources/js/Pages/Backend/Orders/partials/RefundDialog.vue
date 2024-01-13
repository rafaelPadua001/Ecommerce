<template>
    <v-dialog>
        <v-row>
            <v-col>
                <v-sheet>
                    <v-card>
                        <v-card-text>
                            Extorno realizado com sucesso.
                            {{ message }}
                        </v-card-text>

                        <v-card-actions>
                            <v-btn block>Close</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-sheet>
            </v-col>
        </v-row>
    </v-dialog>

</template>

<script>
    export default{
        props: ['message']
    }
</script>