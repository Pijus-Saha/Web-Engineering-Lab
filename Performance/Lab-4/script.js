const students = [{name: "Pijus", roll: 20, subject_scores:{bangla: 78, english: 81, cse115: 85, mat101: 83}, attendance: true},
{name: "Apon", roll: 22, subject_scores: {bangla: 75, english: 83, cse115: 76, mat101: 79}, attendance: false},
{name: "Shrabon", roll: 21, subject_scores: {bangla: 84, english: 74, cse115: 86, mat101: 73}, attendance: true},
{name: "Rimon", roll: 29, subject_scores: {bangla: 74, english: 82, cse115: 75, mat101: 93}, attendance: true}]

for (let i in students) {
    let student = students[i];
    if(student.attendance){
        let totalScore = 0;
    for (let subject in student.subject_scores) {
        totalScore += student.subject_scores[subject];
    }
    console.log("Total Score = "+ totalScore)
    }
    else {
        console.log("NOT ELIGIBLE")
    }
}