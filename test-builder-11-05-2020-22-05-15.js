import { sleep } from "k6";
import http from "k6/http";

export const options = {
  stages: [
    { duration: "1m", target: 20 },
    { duration: "3m", target: 20 },
    { duration: "1m", target: 0 }
  ],
  ext: {
    loadimpact: {
      distribution: {
        "amazon:us:ashburn": { loadZone: "amazon:us:ashburn", percent: 100 }
      }
    }
  }
};

export default function() {
  let response;

  response = http.get("https://kapitalov.org");

  sleep(1);
}