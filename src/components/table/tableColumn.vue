<template slot-scope="scope">
  <div class="table-column" :style="width ? `flex: 0 0 ${width}px;` : `flex: 1;`">
    <div class="title">{{ label }}</div>

    <div class="content-container">
      <div class="content"
        v-for="(row, index) in tableData"
        :key="row + index">
        {{ prop === 'index' ? index + 1 : row[prop] }}
        <slot :row="row"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TableColumn',
  props: ['prop', 'label', 'width'],
  data () {
    return {
      tableData: []
    }
  },
  watch: {
    '$parent.tableData' () {
      const { initTableData } = this

      initTableData()
    }
  },
  methods: {
    initTableData () {
      this.tableData = this.$parent.tableData
    }
  },
  created () {
    const { initTableData } = this

    initTableData()
  }
}
</script>

<style lang="less">
@import '../../assets/style/color.less';

.table-column {
  flex-basis: auto;
  flex-grow: 0;
  flex-shrink: 0;
  text-align: center;
  line-height: 40px;
  font-weight: bold;
  color: @main-color;
  // box-shadow: 0 0 3px @main-color;
  overflow: hidden;

  .title {
    box-sizing: border-box;
    border-top: 1px solid fade(@main-color, 50);
    border-bottom: 1px solid fade(@main-color, 50);
  }

  .content-container {
    .content {
      // box-shadow: 0 0 3px @main-color;
      height: 40px;
      overflow: hidden;
      box-sizing: border-box;
      border-bottom: 1px solid fade(@main-color, 50);
    }
  }
}
</style>
