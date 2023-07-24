var merge = function (intervals) {
  intervals = intervals.map((interval) => {
    interval.sort(function (a, b) {
      return a - b;
    });
    return interval;
  });

  let size = intervals.length;

  let output = [];

  let test = intervals[0];

  if (size != 1) {
    for (let count = 1; count < size; count++) {
      let index1 = null;
      let index2 = null;

      if (intervals[count][0] <= test[1]) {
        const items = [...test, ...intervals[count]];
        items.sort(function (a, b) {
          return a - b;
        });

        index1 = items[0];
        index2 = items[items.length - 1];
        test = [index1, index2];
        output.push(test);
      } else {
        if (test !== output[output.length - 1]) {
          output.push(test);
        }
        index1 = intervals[count][0];
        index2 = intervals[count][1];
        test = [index1, index2];
        output.push(test);
      }
    }
  } else {
    output.push(...intervals);
  }

  return output;
};

console.log(
  merge([
    [1, 4],
    [4, 5],
  ])
);
