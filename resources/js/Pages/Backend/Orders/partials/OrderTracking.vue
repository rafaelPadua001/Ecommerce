<template>
    {{ trackingOrder }}
    <v-dialog transition="dialog-bottom-transition">
                            <v-card>
                                <div>
                                    <v-list>
                                        <v-list-item-group v-if="trackingOrder">
                                            <v-list-item v-for="(value, key) in trackingOrder" :key="key">
                                                <v-list-item-content>
                                                    <v-list-item-title>{{ key }}</v-list-item-title>
                                                    <v-list-item-subtitle v-if="(typeof value === 'object')">
                                                        ID: {{ value.id }}

                                                    </v-list-item-subtitle>
                                                    <v-list-item-subtitle v-else>
                                                        {{ value.id }}
                                                    </v-list-item-subtitle>

                                                    <v-list-item>
                                                        protocol: {{ value.protocol }}
                                                    </v-list-item>
                                                    <v-list-item> status: {{ value.status }}</v-list-item>
                                                    <v-list-item>tracking: <v-btn variant="plain" color="primary"
                                                            size="x-small" @click="trackBack(value.tracking)">{{
                        value.tracking }}</v-btn></v-list-item>
                                                    <v-list-item> created at: {{ value.created_at }}</v-list-item>
                                                    <v-list-item>posted at: {{ value.posted_at }}</v-list-item>
                                                    <v-list-item> delivered at: {{ value.delivered_at }}</v-list-item>
                                                    <v-list-item> canceled at: {{ value.canceled_at }}</v-list-item>
                                                    <v-list-item>expired at: {{ value.expired_at }} </v-list-item>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>

                                        <v-list-item-group v-if="trackingOrder == 0">
                                            <v-list-item-title>Nenhum registro encontrado</v-list-item-title>
                                        </v-list-item-group>
                                    </v-list>
                                </div>
                                <v-card-actions>
                                    <v-btn color="primary" variant="plain" @click="closeTrackingDialog">Close</v-btn>
                                </v-card-actions>

                            </v-card>

                        </v-dialog>
</template>

<script>
    export default {
        name: 'OrderTracking',
        props: ['trackingOrder'],
    }
</script>