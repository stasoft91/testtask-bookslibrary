<template>
  <div>
    <div class="container">
      <div class="py-1 text-center">
        <h2>{{ $t('Book listing') }}</h2>
      </div>

      <div class="row">
        <v-server-table ref="table" :columns="columns" url="/books" :options="options">
          <div slot="child_row" slot-scope="props">
            <a :href="`${baseURL}/photos/${props.row.image}`"><img :src="`${baseURL}/photos/thumbs/200x400/${props.row.image}`" alt=""></a>
          </div>
          <a slot="info" slot-scope="props" target="_blank" class="btn btn-outline-secondary" @click.prevent="toggleRow(props.index)"> {{ $t('Show_image') }} </a>
        </v-server-table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  layout: 'default',
  head () {
    return { title: this.$t('home') }
  },
  data () {
    return {
      baseURL: 'http://lewa.stsft.ru',

      columns: ['info', 'title', 'isbn', 'description']
    }
  },

  computed: {
    ...mapGetters({
      authenticated: 'auth/check'
    }),
    options () {
      return {
        requestFunction (data) {
          return axios.get(this.url, {
            params: data
          }).catch(function (e) {
            this.dispatch('error', e)
          }.bind(this))
        },
        responseAdapter (data) {
          return {
            data: data.data.data,
            count: data.data.count
          }
        },
        texts: {
          count: `${this.$t('Showing')} {from} ${this.$t('to')} {to} ${this.$t('of')} {count} ${this.$t('records')}|{count} ${this.$t('records')}|${this.$t('One record')}`,
          first: this.$t('First'),
          last: this.$t('Last'),
          filter: this.$t('Filter') + ':',
          filterPlaceholder: this.$t('Search query'),
          limit: this.$t('Records') + ':',
          page: this.$t('Page') + ':',
          noResults: 'No matching records',
          filterBy: 'Filter by {column}',
          loading: 'Loading...',
          defaultOption: 'Select {column}',
          columns: 'Columns'
        },
        headings: {
          info: '',
          title: this.$t('title'),
          isbn: this.$t('isbn'),
          description: this.$t('description')
        },
        sortable: ['title', 'isbn'],
        filterable: ['title', 'isbn'],
        perPage: 5,
        perPageValues: [
          5,
          10,
          25,
          50
        ]
      }
    }

  },
  async asyncData () {

  },

  methods: {
    toggleRow (index) {
      this.$refs.table.toggleChildRow(index)
    }
  }

}
</script>

<style>
  .VuePagination {
    text-align: center;
  }

  .vue-title {
    text-align: center;
    margin-bottom: 10px;
  }

  .vue-pagination-ad {
    text-align: center;
  }

  th:nth-child(3) {
    text-align: center;
  }

  .VueTables__child-row-toggler {
    display: none;
  }

  .VueTables__child-row-toggler--closed::before {
    display: none;
  }

  .VueTables__child-row-toggler--open::before {
    display: none;
  }

  .VueTables__table tbody tr:not(.VueTables__child-row) td:first-child,
  .VueTables__table thead tr th:first-child
  {
    display: none;
  }

  .VueTables--server .row .col-md-12 {
    display: flex;
  }
</style>
