/**
 * Resolves with the most accurate position obtained within timeoutMs,
 * stopping early once accuracy is at or below goodEnoughAccuracy (meters).
 */
function getBestPosition(timeoutMs = 6000, goodEnoughAccuracy = 30) {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) {
      reject(new Error('Geolocation not supported on this device/browser.'));
      return;
    }

    let best = null;
    let watchId = null;
    let settled = false;

    const finish = () => {
      if (settled) return;
      settled = true;
      if (watchId !== null) navigator.geolocation.clearWatch(watchId);
      if (best) {
        resolve(best);
      } else {
        reject(new Error('Unable to determine your location.'));
      }
    };

    watchId = navigator.geolocation.watchPosition(
      (pos) => {
        if (!best || pos.coords.accuracy < best.coords.accuracy) {
          best = pos;
        }
        if (pos.coords.accuracy <= goodEnoughAccuracy) {
          finish();
        }
      },
      () => {
        // Ignore individual errors while watching; only reject if we time out with nothing.
      },
      { enableHighAccuracy: true, timeout: timeoutMs, maximumAge: 0 }
    );

    setTimeout(finish, timeoutMs);
  });
}