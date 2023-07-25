var merge = function (intervals) {
  // sorting the intervals elements members
  intervals = intervals.map((interval) => {
    interval.sort(function (a, b) {
      return a - b;
    });
    return interval;
  });

  // sorting the intervals elements.
  intervals = intervals.sort(function (a, b) {
    return (
     a[0] - b[0]
    );
  });

  let size = intervals.length; // the size of the interval array

  
  let collapsed = [];
  let output = [];

  if (size != 1) {
    collapsed = [intervals[0]];
    for (let count = 0; count < size; count++) {
      if (collapsed[collapsed.length - 1][1] >= intervals[count][0]) {
        collapsed[collapsed.length - 1][1] = Math.max(
          collapsed[collapsed.length - 1][1],
          intervals[count][1]
        );
      } else {
        collapsed.push(intervals[count]);
      }
    }
  } else {
    output.push(...intervals);
  }

  output = [...output, ...collapsed];

  return output.sort(function (a, b) {
    return (
      [...a].reduce((a, b) => a + b, 0) - [...b].reduce((a, b) => a + b, 0)
    );
  });
};

console.log(
  merge([[2,3],[2,2],[3,3],[1,3],[5,7],[2,2],[4,6]])
);
