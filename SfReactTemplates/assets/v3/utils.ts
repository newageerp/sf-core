export const filterScopes = (element: any, userState: any, scopesToCheck: any) => {
    if (!element) {
        return true;
    }
    if (!scopesToCheck) {
        return true;
    }
    let isShow = true
    const hideScopes = scopesToCheck.hideScopes ? scopesToCheck.hideScopes : []
    const showScopes = scopesToCheck.showScopes ? scopesToCheck.showScopes : []
    const elementScopes = [
      ...(element.scopes ? element.scopes : []),
      ...userState.scopes
    ]
  
    if (showScopes.length > 0) {
      const intersections = elementScopes.filter(function (n: string) {
        return showScopes.indexOf(n) !== -1
      })
      isShow = intersections.length > 0
      // console.log({ intersections });
    }
    if (hideScopes.length > 0) {
      const intersections = elementScopes.filter(function (n: string) {
        return hideScopes.indexOf(n) !== -1
      })
  
      if (intersections.length > 0) {
        isShow = false
      }
    }
    return isShow
  }