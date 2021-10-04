<template lang="html">

  <v-card>
    <v-card-title>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="batches"
      :search="search"
      :items-per-page="50"
      class="elevation-1"
    >

      <template v-slot:item.data.creationDate="{ item }">
        {{ date(item.data.creationDate) }}
      </template>

      <template v-slot:item.data.completeDate="{ item }">
        <v-chip
          :color="getColor(item.data.completeDate)"
          dark
        >
          {{ date(item.data.completeDate) }}
        </v-chip>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editItem(item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </template>
      <template v-slot:no-data>
        <v-btn
          color="primary"
          @click="initialize"
        >
          Reset
        </v-btn>
      </template>

    </v-data-table>
  </v-card>
</template>

<script>
export default {
  data () {
    return {
      search: '',
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Batch',
          align: 'start',
          sortable: false,
          value: 'data.batchNumber'
        },
        { text: 'Name', value: 'data.name' },
        { text: 'Created', value: 'data.creationDate' },
        { text: 'Completed', value: 'data.completeDate' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      batches: []
    }
  },
  async created () {
    const { data: { batches } } = await this.$axios.get('/batches')
    this.batches = batches
    for (const batch of this.batches) {
      const { pifdata } = JSON.parse(batch.data_json)
      batch.data = pifdata
      delete batch.data_json
    }
  },
  methods: {
    getColor (date) {
      const today = new Date()
      const complete = new Date(date)
      let color = 'success'
      if (today < complete) {
        color = 'error'
      }
      return color
    },
    date (date) {
      const d = new Date(date)
      const day = ('0' + d.getDate()).slice(-2)
      const month = ('0' + (d.getMonth() + 1)).slice(-2)
      const year = d.getFullYear()
      return `${day}/${month}/${year}`
    }
  }
}
</script>

<style lang="sass" scoped>
</style>
